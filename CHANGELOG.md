## v1.5

-   Task: Show Reset Button as soon as default test case selection is changed
    [@dsymonov-jc]
-   Task: Update psr7-validator to the newest version [@jc-skushnir]
-   Task: Message log -> pagination looks broken if too many pages there
    [@dsymonov-jc]
-   Task: Message log no longer identifies session/component IDs [@dsymonov-jc]
-   Task: Edit in the 3 dots options for a session [@dsymonov-jc]
-   Task: Twig template is not being resolved on UI examples [@jc-skushnir]
-   Task: Allow Deleting or Replacing Certificates [@KolyaSirik]
-   Task: Remove platform Component definitions [@KolyaSirik]
-   Task: Component Versioning [@KolyaSirik]
-   Task: Allow variable numbers of requests in a test case [@jc-skushnir]
-   Task: Verify ILP Packet [@KolyaSirik]
-   Task: Uploading an API Spec breaks compared to seeding it at install time
    [@jc-skushnir]
-   Task: HTML code in ITP-simulated responses [@KolyaSirik]
-   Task: Allow Viewing Read-only Test Steps without creating draft
    [@jc-skushnir,@dsymonov-jc]

## v1.4

-   Task: Control Available Session Modes [@KolyaSirik,@Kassaila]
-   Task: Migration for Test Cases does not add "slug" value [@KolyaSirik]
-   Task: Invite User Button Loading State Does not Reset [@jc-skushnir]
-   Task: Control which components may be SUTs [@KolyaSirik]
-   Task: Allow 0-N SUTs [@KolyaSirik]
-   Task: Only draw relevant components on "Select SUT" Page [@KolyaSirik]
-   Task: Remove Simulators [@KolyaSirik]
-   Task: Compliance Session Tweaks [@KolyaSirik,@Kassaila]
-   Task: Compliance session export file -> add more info [@KolyaSirik]
-   Task: Test Case Visual Editor [@jc-skushnir,@Kassaila]

## v1.3

-   Task: Editable Testcases [@jc-skushnir,@Kassaila]
-   Task: Email Notifications for Session Status Changes [@KolyaSirik]
-   Task: Certification Sessions [@KolyaSirik,@Kassaila]
-   Task: Compliance Session Results Page [@KolyaSirik,@Kassaila]
-   Task: Move invitation parameters to service.env [@jc-skushnir]
-   Task: Old Session does not accept incoming messages [@KolyaSirik]

## v1.2

-   Task: Email Invitations [@jc-skushnir,@Kassaila]
-   Task: Implicit Group Admins [@jc-skushnir]
-   Task: Block Registration Without Invitation Code [@jc-skushnir]
-   Task: Questionnaire Definition Parser [@KolyaSirik]
-   Task: Test Case Selection Wizard [@KolyaSirik,@Kassaila]

## v1.0

-   Task: Create Participant flow
    [@jc_agalaguz,@jc_ohladkov,@jc_skushnir,@jc_vmartynov]
-   Task: UI/UX add breadcrumbs and left menu navigation
    [@jc_akokorev,@jc_ohladkov]
-   Task: Session -> Test case page: Move TC Overview to the sidebar instead of
    TC list [@jc_akokorev,@jc_ohladkov]
-   Task: Find a way to manage negative scenarios via Test Cases
    [@jc_ohladkov,@jc_skushnir]
-   Task: Overriding should not be performed for messages sent from SUTs
    [@jc_akokorev]
-   Task: Implement P2P routes for MMO1 [@jc_skushnir]
-   Task: Matching: Test Cases should be performed even if Traceparent was not
    provided [@jc_akokorev]
-   Task: SUTs: Single URL per Session [@jc_akokorev]
-   Task: Add duration per step [@jc_akokorev]
-   Task: MMO1 or 2 as SUT -> Button that initiates Test Execution
    [@jc_akokorev]
-   Task: Test Case: Remove the "RUN" button once the test case has the first
    message initiated by a SUT [@jc_akokorev]
-   Task: Update Eloquent model unit tests after Matching implementation
    [@jc_akokorev,@jc_skushnir]
-   Task: Create Accounts Authorisation flow [@jc_ohladkov,@jc_skushnir]
-   Task: Automated-tests: Sessions and Admin are actions, browser
    [@jc_ohladkov]
-   Task: Add prettier php to ITP [@jc_ohladkov]
-   Task: Color theme switcher [@jc_ohladkov]
-   Bug: P2P flow - PUT /transfers/{ID} is not running [@jc_ohladkov]
-   Bug: Test Case validation not being exectued [@jc_agalaguz]
-   Bug: Add white border in GSMA Logo for dark theme [@jc_ohladkov]
-   Bug: Matching issues with MMO as SUT
    [@jc_akokorev,@jc_ohladkov,@jc_skushnir]

## v0.9

-   Task: Add Cookie popup and Cookie policy [@jc_akokorev,@jc_ohladkov]
-   Task: Dark/Light Mode [@jc_akokorev,@jc_ohladkov]
-   Task: Add text to guide the user to execute the first test case
    [@jc_akokorev,@jc_agalaguz]
-   Task: Add text to guide user once create a new session
    [@jc_ohladkov,@jc_agalaguz]
-   Task: Use Case managment [@jc_akokorev]
-   Task: Test Case draft/public [@jc_akokorev]
-   Task: API Spec Managment [@jc_akokorev]
-   Task: Remove registration code [@jc_akokorev]
-   Bug: 404 page style issue [@jc_akokorev]
-   Bug: Rejected Quote by Payee FSP - Step 8 - PUT quotes ID error
    [@jc_skushnir]
-   Bug: Long session name and description display issue [@jc_ohladkov]
-   Bug: Assertions not showing all data in actual [@jc_vmartynov]

## v0.8

-   Task: Test Case YAML optimization -> use test examples instead of
    "forward/backward" [@jc_akokorev]
-   Task: Overridings -> move them to Request and Response [@jc_akokorev]
-   Task: Show what is once hove the icon [@jc_akokorev]
-   Task: Test Run Step: Scripts- show the details what was tested (regex or
    etc.) [@jc_akokorev,@jc_ohladkov]
-   Task: Create a copy/paste button for the diagram [@jc_ohladkov]
-   Task: Create a copy button for header and body [@jc_ohladkov]
-   Task: Automated-tests: Authentication, browser [@jc_ohladkov]
-   Task: Eloquent model unit tests [@jc_skushnir]
-   Bug: Test steps - Collapsible json issue [@jc_ohladkov]
-   Bug: Rejected Quote by Payee FSP TC - Step 9 is not runing [@jc_skushnir]

## v0.7

-   Task: ITP production: change email "from" to avoid adding email to SPAM
    [@jc_akokorev]
-   Task: User Managment - Verify user by the interface [@jc_akokorev]
-   Task: Change the verify email screen [@jc_akokorev]
-   Task: Changing buttons/advertisement coloors [@jc_ohladkov]
-   Task: Show in TC excution page the data was override [@jc_akokorev]
-   Task: Change button location: Use Case Flow [@jc_akokorev]
-   Task: Run ID in the test case result page [@jc_akokorev]
-   Task: Session create -> At least 1 SUT should be set [@jc_akokorev]
-   Task: ITP -> update Tabler.io and migrate to Single page app using
    https://inertiajs.com/ [@jc_ohladkov,@jc_akokorev]
-   Bug: [NGINX issue] - 502 error when pasting large amount of characters
    [@jc_mbabuta]
-   Bug: TC import - Import fails (502 timeout) with big yaml files
    [@jc_akokorev]

## v0.6

-   Task: ITP: Schema validation [@jc_akokorev]
-   Task: MMO1 or 2 as SUT -> FSP registration during Session creation
    [@jc_akokorev]
-   Task: Ordering test cases in alphabetichal order [@jc_akokorev]
-   Task: Yaml import - Markdown [@jc_akokorev]
-   Task: MMO1 or 2 as SUT -> show Test Data example for every step of TC
    [@jc_ssenokosov]
-   Bug: Latest test run graph - Colour issue [@jc_ssenokosov]
-   Bug: Registration causes 500 error [@jc_akokorev]
-   Bug: TCs fail with all steps passed [@jc_akokorev]

## v0.5

-   Task: Add test runs chart on session page [@jc_ohladkov]
-   Bug: Test Run -> Messages Overriding: Request message is shown as not
    overriden [@jc_akokorev]
-   Bug: Test data - Symbol escaping issue [@jc_akokorev]
-   Bug: Test data - Invalid json issue [@jc_akokorev]
-   Task: MMO1 or 2 as SUT -> Allow users to create own Test Data based on our
    examples [@jc_akokorev]

## v0.4.2

-   Bug: MMO2 -> Rejected Transaction Test Case: steps 10 - 12 are not being
    performed [@jc_ohladkov]
-   Task: Test Case YAML updates [@jc_akokorev]
-   Task: ITP copy change "URL" to "Endpoint" [@jc_akokorev]

## v0.4.1

-   Task: Added TC management (custom TCs import / deletion) [@jc_akokorev]
-   Task: Added Service Provider simulator [@jc_ssenokosov]
-   Task: Implemented transaction type and status mapping for MMO1
    [@jc_ssenokosov]
-   Task: Added registration code for beta users [@jc_akokorev]
-   Task: Removed session deactivation [@jc_akokorev]
-   Task: MMO1 transaction request mapping updates [@jc_ssenokosov]
-   Task: Test run view and flow diagram updates [@jc_ohladkov]
-   Task: Admin links hidden for common user [@jc_akokorev]
-   Bug: Bug fixes and performance improvements [@jc_akokorev]
-   Task: Test Case/Run pages -> Flow: Diagram: Add step # and Response
    [@jc_akokorev]
-   Bug: Transaction Request Rejected by Payer FSP TC - incorrect run status
    [@jc_akokorev]
-   Bug: Test Run -> add back the overview of pass and fail on the top of the
    test run [@jc_akokorev]
-   Bug: Session Test Case pages -> retun Status and Duration [@jc_akokorev]
-   Task: Test Case/Run -> Move test assertions for request and reposne below
    [@jc_akokorev]
-   Bug: Test management - Test cases - Search issue 500 [@jc_akokorev]
-   Task: Test Case/Run page -> add Flow schema as a poup [@jc_akokorev]
-   Task: Test Run/Case page -> Test Data and Test header to become a popup
    [@jc_akokorev]
-   Task: Test data - Make contents as plain json [@jc_akokorev]
-   Test Case/Run page -> add Flow schema as a popup [@jc_akokorev]

## v0.4

-   Task: Save to DB fields from POST transactions and put them into PUT
    callback_url [@jc_ssenokosov]
-   Task: Move project to AWS [@jc_mbabuta]
-   Task: MMO1 -> transactionRequests: do not send 'null' values to Mojaloop #2
    [@jc_ssenokosov]
-   Task: [6] MMO1 simulator -> Missing Callback step from MMO1 to Service
    Provider [@jc_ssenokosov]
-   Tesk: Laravel version update code with new features (casts/route
    bindings/zhttp etc.) [@jc_akokorev]
-   Task: DB updates (Assertions, Use Case steps, Specifications
    versioning)[@jc_akokorev]
-   Task: Test Case Seed via YAML [@jc_akokorev]
-   Task: [[BUG] MMO1 simulator -> response message for 202 shows body of 201 #1
    [@jc_ssenokosov]
-   Task: Session -> Test Run: collapsible messages's json [@jc_ohladkov]
-   Task: Session -> Scenario schema should show SUT and active step's arrows
    [@jc_ohladkov]
-   Task: Update test run view [@jc_ohladkov]
-   Task: [BUG] - Simulator issue - Simulator fails to retrieve
    "transactionRequestState" when amount is 1000.00 [@jc_ohladkov]

## v0.3

-   Task: Session execution - pages (Markup) [@jc_ohladkov]
-   Task: Session execution - Session page [@jc_akokorev]
-   Task: Session execution - Test Case page [@jc_akokorev]
-   Task: Session execution - Test Run page [@jc_akokorev]
-   Tesk: Test Run execution [@jc_akokorev]
-   Task: Homepage (user dashboard)[@jc_akokorev]
-   Task: Mojaloop FSP Merchant Payment implementation [@jc_ohladkov]

## v0.2

-   Task: Session execution - Session page (Markup) [@jc_ohladkov]
-   Task: Session page graph [@jc_ohladkov]
-   Task: Homepage (user dashboard) [@jc_ohladkov]
-   Task: Responsive layout [@jc_ohladkov]
-   Task: Optimize assets [@jc_ohladkov]

## v0.1

-   Task: Login [@jc_akokorev]
-   Task: Login [@jc_ohladkov]
-   Task: Registration [@jc_akokorev]
-   Task: Registration [@jc_ohladkov]
-   Task: Forgot password [@jc_akokorev]
-   Task: Forgot password [@jc_ohladkov]
-   Task: Admin panel - list of users (promote/relegate/block actions)
    [@jc_akokorev]
-   Task: Admin panel - list of users (promote/relegate/block actions)
    [@jc_ohladkov]
-   Task: Admin panel - list of users' sessions [@jc_akokorev]
-   Task: Admin panel - list of users' sessions [@jc_ohladkov]
-   Task: Session creation - Step 1 - choose SUT [@jc_akokorev]
-   Task: Session creation - Step 1 - choose SUT [@jc_ohladkov]
-   Task: Session creation - Step 2 - configure connection [@jc_akokorev]
-   Task: Session creation - Step 2 - configure connection [@jc_ohladkov]
-   Task: Session creation - Step 3 - session info + select Use Cases
    [@jc_akokorev]
-   Task: Session creation - Step 3 - session info + select Use Cases
    [@jc_ohladkov]
-   Task: Maintenance page [@jc_akokorev]
-   Task: Maintenance page [@jc_ohladkov]
-   Task: User profile - edit personal details [@jc_akokorev]
-   Task: User profile - edit personal details [@jc_ohladkov]
-   Task: User profile - Password reset [@jc_akokorev]
-   Task: User profile - Password reset [@jc_ohladkov]
-   Task: Session page - view 1 [@jc_ohladkov]
-   Task: Session page - view 2 [@jc_ohladkov]
-   Task: Session page - view 1 result [@jc_ohladkov]
