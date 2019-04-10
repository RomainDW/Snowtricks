Feature:
  homepage

  Background:
    Given I am on "/"

  Scenario: Chargement de la page d'accueil
    Then I should see "Bienvenue sur SnowTricks !"
    And I should see "Découvrez les dernière figures"