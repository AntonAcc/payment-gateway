Test Assignment

Objective:

Develop an API endpoint that, based on a parameter passed in the URL, sends a request to one of two
(potentially more in the future) external systems and returns a unified response.

Additionally, implement the same functionality for CLI. Based on the parameter passed in the CLI
command, the server should send a request to one of two external systems and return a unified
response in the console.

The request is a regular, one-time purchase, no 3DS, server-2-server.<br />
External systems are Shift4 and ACI.<br />

Technical Requirements:

PHP 8<br />
Symfony 6.4<br />

Bonus Points For:

Implementing any tests (unit/integration/functional)<br />
Including a simple Dockerfile<br />

Detailed Description:

Both CLI Command and API Endpoint:

1. Should accept input params:<br />
   amount<br />
   currency<br />
   card number<br />
   card exp year<br />
   card exp month<br />
   card cvv<br />
   <br />
2. Return a unified response regardless of which external system is called. The response should contain:<br />
   transaction ID<br />
   date of creating<br />
   amount<br />
   currency<br />
   card bin<br />

<br />
API Endpoint exmaple: /app/example/{aci|shift4}<br />
CLI Command example: bin/console app:example {aci|shift4}<br />
Based on the parameter value {aci|shift4} , the server should send a request to one of two external systems.

External Systems:

Shift4

Doc: https://dev.shift4.com/docs/api#charge-create<br />
These parameters could be hardcoded because they are limited to the test mode:<br />

auth key<br />
card number<br />

ACI

Doc: https://docs.oppwa.com/integrations/server-to-server#syncPayment<br />
Synchronous payment → Debit → Perform debit payment<br />
These parameters could be hardcoded because they are limited to the test mode:<br />

auth key<br />
entity id<br />
payment brand<br />
card number<br />
currency (EUR)<br />

Expectations:

The API endpoint and CLI command should be properly documented.<br />
The code should follow best practices and be well-organized.<br />
Ensure error handling and edge cases are considered.<br />
Use domain-specific terminology as provided in the attached documents.<br />

Submission:

Provide the complete source code in the public repository (GitHub / GitLab / Bitbucket)<br />
Include instructions on how to run the application and tests.<br />
Dockerfile (if implemented) should allow easy setup of the environment.<br />