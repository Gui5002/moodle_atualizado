@mod @mod_evadeclaration @verify_evadeclaration_code
Feature: Verify declaration  authenticity by code, in verification page
  In order to verify the authenticity 
  As a any user (no need to be logged)
  I need to issue a declaration, get the code, and verify it at 
  verification page

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | student1 | Tumé      | Arandú   | student1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course Verify | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role |
      | student1 | C1 | student |
    And I log in as "admin"
    And I am on site homepage
    And I follow "Course Verify"
    And I turn editing mode on
    And I add a "Simple declaration" to section "1" and I fill the form with:
      | declaration Name | Test Simple declaration |
      | declaration Text | Test Simple declaration |
	And I issue a "Test Simple declaration" to "student1"
	And I log out

  Scenario: Verify declaration authenticity by code
    Given I am on "declaration verification page"
    And I set "student1" declaration "Test Simple declaration" code 
    And I press "Verify declaration"
    Then I should see "Tumé Arandú"
    And I should see the "student1" declaration "Test Simple declaration" code
    And I should see "Course Verify"
	