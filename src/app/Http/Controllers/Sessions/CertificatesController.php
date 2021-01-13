<?php

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use File;
use Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class CertificatesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function download(): BinaryFileResponse
    {
        $rootCaPath = env('ROOT_CA_PATH');
        $rootCaKeyPath = env('ROOT_CA_KEY_PATH');

        $clientPrivateKey = openssl_pkey_new([
            'private_key_bits' => 4096,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        $csr = openssl_csr_new(
            [
                'countryName' => 'UK',
                'organizationName' => 'ITP',
            ],
            $clientPrivateKey
        );

        $x509 = openssl_csr_sign(
            $csr,
            File::get($rootCaPath),
            File::get($rootCaKeyPath),
            365
        );

        openssl_x509_export($x509, $csrout);
        openssl_pkey_export($clientPrivateKey, $pkeyout);

        $userId = auth()->id();
        $filePath = storage_path(
            "app/certificates/user-certificates-{$userId}.zip"
        );

        File::delete($filePath);

        $zip = new ZipArchive();
        $zip->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $zip->addFile($rootCaPath, 'RootCA.crt');
        $zip->addFromString('client.crt', $csrout);
        $zip->addFromString('client.key', $pkeyout);

        $zip->close();

        app()->terminating(function () use ($filePath) {
            File::delete($filePath);
        });

        return Response::download($filePath);
    }
}
