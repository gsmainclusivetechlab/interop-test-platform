P2P

USE CASE:

1.	**John Wilson wants to send money to Kelly Hills. John knows that Kelly’s phone number is +33555123456, so he requests a transfer of 123.45 USD dollars for his bank: FirstBank.
2.	FirstBank check if the number +33555123456 exists in the bank, if yes, it proceeds with …. If no, FirstBank contacts the Switch (using  GET /parties/<Type>/<ID>) to get information about this user.
3.	Switch reads its database trying to locate the account holder identified by +33555123456. If the Switch can locate the account party, called here SecondBank, it sends a request to collect information (GET /parties/<Type>/<ID>) …, if not…
4.	SecondBank receives the request and check for more information regarding +33555123456. After verifying that the account exists and it is owned by Kelly Hills, SecondBank sends a Callback to Switch. 
5.	Switch then sends the same Callback to FirstBank
