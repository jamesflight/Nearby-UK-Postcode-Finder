<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Jflight\PostcodeFinder\UkPostcode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

    /**
     * @Given /^I have instantiated a postcode finder object with postcode "([^"]*)"$/
     */
    public function iHaveInstantiatedAPostcodeFinderObjectWithPostcode($postcode)
    {
        $this->postcode = new UkPostcode($postcode);
    }

    /**
     * @When /^I provide get nearby postcodes within the radius "([^"]*)"$/
     */
    public function iProvideGetNearbyPostcodesWithinTheRadius($radius)
    {
        $this->nearbyPostcodes = $this->postcode->findNearestPostcodes($radius);
    }

    /**
     * @Then /^it should return the following postcodes: "([^"]*)"$/
     */
    public function itShouldReturnTheFollowingPostcodes($postcodes)
    {
        $postcodes = str_replace(' ', '', $postcodes);
        $postcodes = explode(',', $postcodes);

        foreach ($this->nearbyPostcodes as $postcode)
        {
            if (! in_array($postcode->postcode, $postcodes))
            {
                throw new \Exception('A postcode was returned that is not in the expected list.');
            }
        }


    }
}
