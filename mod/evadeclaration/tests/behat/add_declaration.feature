@mod @mod_evadeclaration @add_evadeclaration
Feature: Add a evadeclaration
  In order to create declaration for students
  As a teacher
  I need to create a declaration

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Terry1    | Teacher1 | teacher1@example.com |
      | student1 | Tumé      | Arandú   | student1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | student1 | C1 | student |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    And I add a "Simple declaration" to section "2" and I fill the form with:
      | declaration Name | Test Simple declaration |
      | declaration Text | Test Simple declaration |
      | Print Grade | -1 |
    And I log out

  @javascript 
  Scenario: Add a very basic declaration and verify if a student can donwload
    When I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "Test Simple declaration"
    Then I press "Get declaration"
    And I should see a pop-up window

  @javascript
  Scenario: Show declaration greyed-out to students when grade condition is not satisfied
    Given  I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    And I add a "Assignment" to section "1" and I fill the form with:
      | Assignment name | Grade assignment |
      | Description | Grade this assignment |
      | assignsubmission_onlinetext_enabled | 1 |
      | assignsubmission_file_enabled | 0 |
    And I setup a grade restrinction to "Test Simple declaration" with "Grade assignment" min grade "20"
    And I log out
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "Test Simple declaration" activity should be dimmed
    And "Test Simple declaration" "link" should not exist
    And I should see "Restricted Not available unless: You achieve a required score in Grade assignment"
    And I follow "Grade assignment"
    And I press "Add submission"
    And I set the following fields to these values:
      | Online text | I'm the student submission |
    And I press "Save changes"
    And I should see "Submitted for grading"
    And I log out
    And I log in as "teacher1"
    And I am on site homepage
    And I am on "Course 1" course homepage
    And I navigate to "View > Grader report" in the course gradebook
    And I turn editing mode on
    And I give the grade "20" to the user "Tumé Arandú" for the grade item "Grade assignment"
    And I press "Save changes"
    And I am on "Course 1" course homepage
    And I log out
    And I log in as "student1"
    And I am on site homepage
    And I am on "Course 1" course homepage
    And "Test Simple declaration" activity should be visible
    And I should not see "Not available unless: You achieve a required score in Grade assignment"
    And I follow "Test Simple declaration"
    Then I press "Get declaration"
    And I should see a pop-up window
    
    
	