<?php

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class CertificatesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function download()
    {
        $userId = auth()->id();
        $filePath = storage_path(
            "app/certificates/user-certificates-{$userId}.zip"
        );
        if (!File::exists($filePath)) {
            return redirect()
                ->back()
                ->with('error', 'Certificates not found');
        }
        app()->terminating(function () use ($filePath) {
            File::delete($filePath);
        });

        return response()->download($filePath);
    }

    public function uploadCsr()
    {
        request()->validate([
            'file' => ['required', 'mimetypes:text/plain'],
        ]);

        try {
            $rootCaPath = env('ROOT_CA_PATH');
            $rootCaKeyPath = env('ROOT_CA_KEY_PATH');

            $csr = request()->file('file')->get();

            $x509 = openssl_csr_sign(
                $csr,
                File::get($rootCaPath),
                File::get($rootCaKeyPath),
                365
            );

            openssl_x509_export($x509, $csrout);

            $userId = auth()->id();
            $filePath = storage_path(
                "app/certificates/user-certificates-{$userId}.zip"
            );

            File::delete($filePath);

            $zip = new ZipArchive();
            $zip->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            $zip->addFile($rootCaPath, 'RootCA.crt');
            $zip->addFromString('client.crt', $csrout);

            $zip->close();
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'file' => $e->getMessage(),
            ]);
        }

        return back();
    }

    public function downloadCsr(): StreamedResponse
    {
        $clientPrivateKey = openssl_pkey_get_private(
            File::get(env('CLIENT_KEY_PATH'))
        );
        $csr = openssl_csr_new(
            [
                'countryName' => 'UK',
                'organizationName' => 'ITP',
            ],
            $clientPrivateKey
        );
        openssl_csr_export($csr, $csrout);

        return response()->streamDownload(
            function () use ($csrout) { echo $csrout; },
            'client.csr'
        );
    }
}
