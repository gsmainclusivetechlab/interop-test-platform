export default [
  {
    title: "General",
    items: [
      {
        title: "What is the Mobile Money API?",
        text: "The GSMA Mobile Money API is an initiative developed through collaboration between the mobile money industry and the GSMA, which provides a harmonised API Specification for all the common mobile money use cases which is both easy to use and secure."
      },
      {
        title: "Who created and maintains the Mobile Money API?",
        text: "The API is a GSMA-led industry initiative, for and by the Mobile Money industry, mobile money operators and their technology platform providers. It is maintained by the GSMA working with the industry as it continues to evolve and grow."
      },
      {
        title: "Who can use the Mobile Money API?",
        text: "The API is designed to be used by any party in the Mobile Money industry. This includes Mobile Money Operators, other Payment Service Providers, Retail and eCommerce Merchants, Application Developers, companies receiving Bill Payments, and others."
      },
      {
        title: "Which use cases does the Mobile Money API support?",
        text: "The Mobile Money API supports many use cases, including Domestic and International Remittance, Merchant Payments, Bill Payments, Authorisation to Debit and Direct Debits, Linking Mobile Money accounts to other financial institution accounts, and Ability to view Account and Account Holder Information, and others."
      },
      {
        title: "What are the advantages of the Mobile Money API?",
        text: "Adoption of the Mobile Money API leads to key benefits including growth of your mobile money ecosystem from faster partner on-boarding as partners will only have to integrate to one common API. Adopting a harmonised API also leads to easier maintenance and evolution of your common Mobile Money operations by providing support for advanced features such as multi-wallets and batch payments."
      },
      {
        title: "Is the GSMA Mobile Money API free to use?",
        text: "Yes, the GSMA represents the interests of mobile operators worldwide, uniting more than 750 operators with almost 400 companies in the broader mobile ecosystem and the assets we maintain are there to support our members and our industry. We encourage use of the harmonised API and place no restrictions on its use."
      },
      {
        title: "Are there any alternative API standards?",
        text: "There is no other harmonised API that supports the range of Mobile Money use cases provided by the GSMA Mobile Money API."
      },
    ],
  },

  {
    title: "Technical",
    items: [
      {
        title: "Which API Version should we implement?",
        text: "The current version of the Mobile Money API is v1.1 and this version should be implemented by all new adopters.  We encourage existing adopters who have implemented the previous version of the API to migrate to the latest version which is backwards compatible."
      },
      {
        title: "How did you decide on which APIs to include in the current Specification?",
        text: "The current set of APIs have been selected to cover the most common mobile money use cases that are used in the industry today."
      },
      {
        title: "Can I implement only some of the APIs and still conform to the API Specification?",
        text: "Yes, you can implement only the APIs which are relevant for your business."
      },
      {
        title: "Is the Mobile Money API easy to implement?",
        text: "Yes, the Mobile Money API is simple to implement as the API is based upon REST/JSON which is used extensively by the developer community, and most of the APIs are simply defined and only require a small number of mandatory fields. Developers can additionally use Swagger Codegen to rapidly generate Mobile Money API stubs for development and testing, and the GSMA can also support you in implementing the API."
      },
      {
        title: "Why are only REST and JSON supported over other API designs and data formats?",
        text: "The design decision was based on REST and JSON being the best options for an API in terms of simplicity, ease of development, and because they are the most commonly used and understood architecture and data format today for all kinds of APIs."
      },
      {
        title: "Why was OAS chosen as the API definition framework over other API frameworks?",
        text: "OAS was chosen as it provides the best modelling flexibility and includes a comprehensive toolset to facilitate API implementation including client and server side SDKs."
      },
      {
        title: "Does the API support synchronous and asynchronous patterns?",
        text: "Yes, the API supports synchronous and asynchronous patterns for resource creation and update requests, and synchronous patterns only for read requests, which provides support for the most commonly used request paradigms."
      },
      {
        title: "Can you add support for a Mobile Money use case which is not included in the Specification?",
        text: "Please contact us to let us know if you use APIs which are not currently included in the API Specification. We have a living roadmap and will continue to update the Spec over time."
      },
    ],
  },

  {
    title: "Security",
    items: [
      {
        title: "Are there security recommendations to consider when implementing the Mobile Money API?",
        text: "Yes, we provide a comprehensive set of security guidelines, please see our latest Security Design and Implementation guidelines in the Developer Portal here."
      },
      {
        title: "Do I need to implement an API Gateway to host the Mobile Money API?",
        text: "No. However we recommend you use an API Gateway as this enables efficient developer on-boarding processes, making the implementation process simpler and allowing third parties to more rapidly exploit the benefits of the API. Off the shelf API Gateways do support the Mobile Money API security guidelines, including OAuth2."
      },
    ],
  },
];
