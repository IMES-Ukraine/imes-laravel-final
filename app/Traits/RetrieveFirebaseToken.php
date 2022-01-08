<?php
namespace App\Traits;

use Kreait\Firebase\JWT\CustomTokenGenerator;

trait RetrieveFirebaseToken
{

    protected function retrieveFirebaseToken(){
        $clientEmail = "firebase-adminsdk-9r636@imes-v1.iam.gserviceaccount.com";
        $privateKey = "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCtMO0mT4tjcsqG\nrpcstguUepDpEKII3m5jeNPqdjslTfLsVYa1pEUKYy0jiG+ra5CDEnoXQwnBeSb3\nEOW4Q1WlW+YIoJvoNg4d47LGCyrrNVhAdZPwjFMOtXQNzxP5xgzBlTuqqbzduqAE\niADuv96m881JK+FiGmexmNXoqQvpOhqmXRm2Y7gD5M/cL5dvX+SjIKU0vAkKxgrh\n5WneyWVnblsPU/eH4Y5dOz5rujK9aMRN/3bUaMdvF3vs6O7DH3FdzCSi0niQfkWG\nm9O1T8L3b5soqsFaJhkmwn3wBkmc2ACVjk95pd4y3zjYOPy5iWUkFXPYfNedXgcR\nrklyc/QjAgMBAAECggEAN7hfBA1hfhxndk9jidoD/cA1MRbN2uadQ1mTbIKfYtAi\nUNDvZy8zmzTvR9hUfSU9OD5Qk31Y/SsaUzDXh6H6Qxo+9xWdM2d4wTsJwFfFdikS\nfKKXdzPeYQQWE39FIP4MCpgVu0Gi5v9tDignjKikXLhhmqIIgESYifXvFEyW7XDh\naJVeLFekOz9hI092DTjz3ZWBEfTN6pqJADw4y2AavlCTvSRE6HjXaAww3DsVG5EO\nJisvOLmr1eI/zkjtHfcKW8yTgs+CLfAr6zoim6lPTD5rmDoFXY8BGad0aPL5OOHn\npvoRZmUkoBzRsd7m+dHFDxIO3e59FAwzvEKGnBCeQQKBgQDhLawYRA+MjM5my5Kq\n+ut+qKJDAqY58AS2GMrvx8IICW07BA91eNtQzd66bKKGImpJf7bf+9WNJkfUOfcQ\nhuRUBhonnZOQWYxVk/cOCUkUwqzL4eKSPGlgbeiqkWrGMHo17rk1kRtuuqKEu1bW\nn/eINgqdFthDQOhhs6nveRuLQwKBgQDE5ZoV9PKPA2jeohhliDiBDBHhdJvseC1h\nuAeuiIPZLe7W9OQa8gvKYGQ23p13tb1L3Vvp232GOMJgUEme62d2hcjYwwO33Md+\nMNrsrCLo2qnfkw75FIeSKlRFLXFLvrH5HjWbq1eDsawgfXj7gu9zKywq6HqLzqIc\nM3VdVHm1oQKBgQDdrH2fwdjgz3kdv0ia47vTi6V5fHEx1jR9I3kchjVgfg83Ku8h\nASI+sPyT4AA7NkQWKRFZ/OwlMUtDBPFRJso+YWd8Gmc7krxRPwHWopN8SY2chLns\npmZClNCYoeFQTEbzMxjHQBuWbUsyic6aKQ6g3DqknOm3g32BOUKQylOwSwKBgDkB\n/VYEIMKViySCrCuhgDKk6vxrBcY2mXUkklzPO9WCvhdQukau9Aj5VaqpU9BYN2Gi\nyrzwBuz4vBakyFwR7lfbrmZMOGRgsQXxYUGLKWRgAzUvX/NwJTyFsvaAjMAQi3kE\nbSMQPftsUtjpW0D/DRM8WcJmoyydfERMBdwq3D5hAoGBALwQRruisdc9UHuXp2Ze\nVqqlZENKvJQlV/MYAOaqezJ9PyTQTsc3HTksw9FdM0It9daxvgd/gykOFZBrhbxk\ntbkf6oqfJVUlZ6uifs9PUZenQMd1VXJxMvTReNaCO293mriYE3npKmoXcJE+as4+\nZlBt6tOAltKpzelS+yhw2OtL\n-----END PRIVATE KEY-----\n";

        $generator = CustomTokenGenerator::withClientEmailAndPrivateKey($clientEmail, $privateKey);
        $token = $generator->createCustomToken('uid', ['first_claim' => 'first_value']);

        return $token->toString();
    }
}
