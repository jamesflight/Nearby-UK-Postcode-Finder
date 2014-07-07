Feature: Nearby UK postcode finder

  Scenario Outline: Find postcodes in given radius
    Given I have instantiated a postcode finder object with postcode "<postcode>"
    When I provide get nearby postcodes within the radius "<radius>"
    Then it should return the following postcodes: "<nearby_postcodes>"
  Examples:
    | postcode  | radius    | nearby_postcodes                                |
    | CF11      | 5         | CF11, CF10, CF30, CF91, CF95, CF99, CF64, CF24  |
    | BS7       | 1.61      | BS7, BS6, BS2                                   |
    | SG4       | 10        | SG4, SG5, SG6, SG1, SG3, SG2, SG1, LU2, AL6     |