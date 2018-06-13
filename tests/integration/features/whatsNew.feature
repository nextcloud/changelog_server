Feature: testing the response of the Changelog Server
  Scenario: Request against a version which does not have any info available
    Given the version of interest is "11.0.0"
    When the request is sent
    Then the response is empty
    And the return code is "404"

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
    And the received Etag is "eb7e047b4d0f16fc6de0859abc74a3f1"
    And the response is
    """
    <?xml version="1.0" encoding="utf-8" ?>
    <release xmlns:xsi= "http://www.w3.org/2001/XMLSchema-instance"
             xsi:noNamespaceSchemaLocation="../schema.xsd"
             version="13.0.0">
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
    </release>
    """

  Scenario: Request against a valid version with matching an valid etag
    Given the version of interest is "13.0.0"
    And the known Etag is "eb7e047b4d0f16fc6de0859abc74a3f1"
    When the request is sent
    Then the return code is "304"
    And the response is empty

  Scenario: Request against a valid version with outdated etag
    Given the version of interest is "13.0.0"
    And the known Etag is "abcdefabcdef00011122233344455566"
    When the request is sent
    Then the return code is "200"
    And the received Etag is "eb7e047b4d0f16fc6de0859abc74a3f1"
    And the response is
    """
    <?xml version="1.0" encoding="utf-8" ?>
    <release xmlns:xsi= "http://www.w3.org/2001/XMLSchema-instance"
             xsi:noNamespaceSchemaLocation="../schema.xsd"
             version="13.0.0">
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
    </release>
    """
