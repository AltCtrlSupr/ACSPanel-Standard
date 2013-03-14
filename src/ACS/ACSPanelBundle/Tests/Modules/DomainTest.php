<?php 

namespace ACS\ACSPanelBundle\Tests\Modules;

use ACS\ACSPanelBundle\Modules\Domain;

class DomainTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRegDomainWithAlias()
    {
        $domain = new Domain('alias.example.com');
        $result = $domain->get_reg_domain();
        $this->assertEquals('example.com', $result);
    }

    public function testGetRegDomain()
    {
        $domain = new Domain('example.com');
        $result = $domain->get_reg_domain();
        $this->assertEquals('example.com', $result);
    }

    public function testGetEtld()
    {
        $domain = new Domain('example.com');
        $result = $domain->get_etld();
        $this->assertEquals('com.', $result);
    }


}
?>
