<?php

use Jflight\PostcodeFinder\Postcode;

class PostcodeTest extends TestCase {

    public function setUp()
    {
        $this->postcode = new TestPostcode;
    }

    public function test_it_can_make_new_postcode()
    {
        $postcode2 = $this->postcode->create('AA1');
        $this->assertInstanceOf('TestPostcode', $postcode2);
        $this->assertEquals('AA1', $postcode2->postcode);
    }

    /**
     * @expectedException Exception
     */
    public function test_throws_exception_if_try_to_find_nearest_postcodes_before_postcode_set()
    {
        $this->postcode->findNearestPostcodes(5);
    }

    /**
     * @expectedException Exception
     */
    public function test_throws_exception_if_try_to_find_nearest_postcodes_and_current_postcode_not_in_list()
    {
        $this->postcode->postcode = 'Not_in_list';
        $this->postcode->findNearestPostcodes(5);
    }

    public function test_it_can_return_all_postcodes()
    {
        $all = $this->postcode->all();
        $this->assertInstanceOf('TestPostCode',$all[0]);
        $this->assertEquals('AA1', $all[0]->postcode);
        $this->assertEquals('11', $all[0]->latitude);
        $this->assertEquals('22', $all[0]->longitude);

        $this->assertInstanceOf('TestPostCode',$all[1]);
        $this->assertEquals('AA2', $all[1]->postcode);
        $this->assertEquals('15', $all[1]->latitude);
        $this->assertEquals('24', $all[1]->longitude);
    }

    public function test_it_can_find_nearest_postcodes()
    {
        $postcode2 = $this->postcode->create('AA1');
        $postcodesArray = $postcode2->findNearestPostcodes(1);
        $this->assertInstanceOf('TestPostCode', $postcodesArray[0]);
        $this->assertEquals('AA2', $postcodesArray[1]->postcode);
    }
}

class TestPostcode extends Postcode
{
    protected $file = '/../tests/stubs/TestPostcode.csv';

    protected function getDistance()
    {
        return 0.5;
    }

    protected function getGeotoolsCoordinate()
    {
        return null;
    }
}