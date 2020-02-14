Feature: testing the response of the Changelog Server
  Scenario: Request against a version which does not have any info available
    Given the version of interest is "11.0.0"
    When the request is sent
    Then the response is empty
    And the return code is "200"
    And the etag is "d41d8cd98f00b204e9800998ecf8427e"

  Scenario: Request against an invalid version (wrong format)
    Given the version of interest is "eleven oh oh"
    When the request is sent
    Then the response is empty
    And the return code is "400"

  Scenario: Request against an invalid version (too detailed)
    Given the version of interest is "11.0.0.7"
    When the request is sent
    Then the response is empty
    And the return code is "400"

  Scenario: Request against a valid version, expecting info
    Given the version of interest is "13.0.0"
    When the request is sent
    Then the return code is "200"
    And the response contains
    """
      <changelog href="https://nextcloud.com/changelog/#13-0-0"/>
      <whatsNew lang="en">
        <regular>
          <item>Refined user interface</item>
          <item>End-to-end Encryption</item>
          <item>Video and Text Chat</item>
        </regular>
        <admin>
          <item>Changes to the Nginx configuration</item>
          <item>Theming: CSS files were consolidated</item>
        </admin>
      </whatsNew>
    """

  Scenario: Request against a valid version, expecting info
    Given the version of interest is "14.0.0"
    When the request is sent
    Then the return code is "200"
    And the response contains
    """
      <changelog href="https://nextcloud.com/changelog/#14-0-0"/>
      <whatsNew lang="en">
        <regular>
          <item>Verify a share recipient in a video call; 2FA with Signal &amp; Telegram</item>
          <item>Add a note to shares; extended share views; search by comments</item>
          <item>Accessibility improvements including high contrast theme</item>
        </regular>
        <admin>
          <item>Requires PHP &gt;= 7.0, log format, syslog tag &amp; nginx config changed</item>
          <item>Improved GDPR compliance through apps; separate audit log</item>
          <item>Kerberos auth to Samba; multi-IdP SAML</item>
        </admin>
      </whatsNew>
    """

  Scenario: Request against a valid version with matching an valid etag
    Given the version of interest is "13.0.0"
    And the request is sent
    And remembering the received Etag
    When the request is sent
    Then the return code is "304"
    And the response is empty

  Scenario: Request against a valid version with matching an valid etag
    Given the version of interest is "14.0.0"
    And the request is sent
    And remembering the received Etag
    When the request is sent
    Then the return code is "304"
    And the response is empty

  Scenario: Request against a valid version with outdated etag
    Given the version of interest is "14.0.0"
    And the known Etag is "abcdefabcdef00011122233344455566"
    When the request is sent
    Then the return code is "200"

  Scenario: Request against a valid version with outdated etag
    Given the version of interest is "13.0.0"
    And the known Etag is "abcdefabcdef00011122233344455566"
    When the request is sent
    Then the return code is "200"
    And the response contains
    """
      <changelog href="https://nextcloud.com/changelog/#13-0-0"/>
      <whatsNew lang="en">
        <regular>
          <item>Refined user interface</item>
          <item>End-to-end Encryption</item>
          <item>Video and Text Chat</item>
        </regular>
        <admin>
          <item>Changes to the Nginx configuration</item>
          <item>Theming: CSS files were consolidated</item>
        </admin>
      </whatsNew>
    """

  Scenario: Ensure current release data of 14 series is returned
    Given the version of interest is "14.0.2"
    When the request is sent
    Then the return code is "200"
    And the response contains
    """
      <changelog href="https://nextcloud.com/changelog/#14-0-2"/>
      <whatsNew lang="en">
        <regular>
          <item>Revised file upload handling</item>
          <item>Contact menus on mentions work again</item>
          <item>Detail improvements (e.g. mobile file view)</item>
        </regular>
        <admin>
          <item>"Add group" button on users page is back</item>
          <item>"None" auth type available again in mail settings</item>
          <item>Fixed unavailable files under some conditions</item>
        </admin>
      </whatsNew>
    """


  Scenario: Ensure current release data of 15 series is returned
    Given the version of interest is "15.0.0"
    When the request is sent
    Then the return code is "200"
    And the response contains
    """
      <changelog href="https://nextcloud.com/changelog/#15-0-0"/>
      <whatsNew lang="en">
        <regular>
          <item>Nextcloud Social &amp; multiple link shares</item>
          <item>New design, grid view &amp; 2x faster loading of Files</item>
          <item>sidebar with Collabora Online &amp; Talk in sidebar</item>
        </regular>
        <admin>
          <item>enforcement of 2FA &amp; security hardenings</item>
          <item>automatic PDF conversion &amp; script execution</item>
          <item>Updated Dashboard, Full Text Search &amp; Group folders</item>
        </admin>
      </whatsNew>
    """

  Scenario: Ensure current release data of 16 series is returned
    Given the version of interest is "16.0.0"
    When the request is sent
    Then the return code is "200"
    And the response contains
    """
      <changelog href="https://nextcloud.com/changelog/#16-0-0"/>
      <whatsNew lang="en">
        <regular>
          <item>Smart recommendations for files, share recipients</item>
          <item>Projects: group files, chats, tasks for easy finding</item>
          <item>right-click in Files, new document viewer &amp; more</item>
        </regular>
        <admin>
          <item>Machine-learning powered suspicious login detection</item>
          <item>Access Control Lists in Groupfolders</item>
          <item>Variables for external storage (LDAP provides '$home')</item>
        </admin>
      </whatsNew>
    """


  Scenario: Ensure current release data of 17 series is returned
    Given the version of interest is "17.0.0"
    When the request is sent
    Then the return code is "200"
    And the response contains
    """
      <changelog href="https://nextcloud.com/changelog/#17-0-0"/>
      <whatsNew lang="en">
        <regular>
          <item>Remote wipe</item>
          <item>Our new distraction-free, collaborative rich text editor</item>
          <item>secure view gains enforceable document watermarks</item>
        </regular>
        <admin>
          <item>Setup two-factor authentication after first login</item>
          <item>LDAP write support</item>
          <item>S3 versioning, IBM Spectrum Scale &amp; more</item>
        </admin>
      </whatsNew>
    """

  Scenario: Ensure current release data of 18 series is returned
    Given the version of interest is "18.0.0"
    When the request is sent
    Then the return code is "200"
    And the response contains
    """
      <changelog href="https://nextcloud.com/changelog/#18-0-0"/>
      <whatsNew lang="en">
        <regular>
          <item>Workspaces lets you add a description above your folders</item>
          <item>File locking prevents conflicts editing shared files with others</item>
          <item>Flow lets you automate repetitive, boring tasks</item>
        </regular>
        <admin>
          <item>ONLYOFFICE can be installed without docker or reverse proxy</item>
          <item>Calendar, Mail and Talk rewrites bring many new improvements</item>
          <item>Flow lets you easily automate tasks, protect files and process documents</item>
        </admin>
      </whatsNew>
    """
