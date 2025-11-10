@mod @mod_evadeclaration @issued_tab_evadeclaration
Feature: List issued declarations
  In order to list issued declarations
  As a teacher
  I need to create a declaration and students issue then

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Tupã      | Xingú    | teacher1@example.com |
      | student1 | Tumé      | Arandú   | student1@example.com |
      | student2 | Arasy     | Guaraní  | student2@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | student1 | C1 | student |
      | student2 | C1 | student |
    And I log in as "teacher1"
    #Moodle 3.2 and below
    #And I follow "Course 1"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    And I add a "Simple declaration" to section "1" and I fill the form with:
      | declaration Name | Test Simple declaration |
      | declaration Text | Test Simple declaration |
	And I log out

  Scenario: Verify if issued declarations are displyed
    Given I log in as "student1"
    #Moodle 3.2 and below
    #And I follow "Course 1"
    And I am on "Course 1" course homepage
    And I follow "Test Simple declaration"
    And I press "Get declaration"
    And I am on site homepage
    And I log out
    Then I log in as "teacher1"
    #Moodle 3.2 and below
    #And I follow "Course 1"
    And I am on "Course 1" course homepage
    And I follow "Test Simple declaration"
    And I click on "Issued declarations" "link"
    And I should see "Tumé Arandú"
    But "Arasy Guaraní" "text" should not exist in the ".generaltable" "css_element"
    And I am on site homepage
    And I log out
    And I log in as "student2"
    #Moodle 3.2 and below
    #And I follow "Course 1"
    And I am on "Course 1" course homepage
    And I follow "Test Simple declaration"
    And I press "Get declaration"
    And I am on site homepage
    And I log out
    And I log in as "teacher1"
    #Moodle 3.2 and below
    #And I follow "Course 1"
    And I am on "Course 1" course homepage
    And I follow "Test Simple declaration"
    And I click on "Issued declarations" "link"
    Then I should see "Tumé Arandú"
    And I should see "Arasy Guaraní"
    But "Tupã Xingú" "text" should not exist in the ".generaltable" "css_element" 
    
 # Test if teacher declaration is save
 #Test export as 
 # test no declaration is issued
	