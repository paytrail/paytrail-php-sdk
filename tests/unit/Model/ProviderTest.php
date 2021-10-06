<?php

use OpMerchantServices\SDK\Model\Provider;
use PHPUnit\Framework\TestCase;

class ProviderTest extends TestCase
{
    public function testProvider()
    {
        $p = new Provider();
        $group = 'Group1';
        $icon = 'https://somedomain.com/icons/icon.png';
        $id = 'someId';
        $providerName = 'Provider name';
        $params = ['param1' => 1, 'param2' => 2];
        $svg = 'https://somedomain.com/icons/icon.svg';
        $url = 'https://somedomain.com/provider-url';

        $p->setGroup($group);
        $p->setIcon($icon);
        $p->setId($id);
        $p->setName($providerName);
        $p->setParameters($params);
        $p->setSvg($svg);
        $p->setUrl($url);

        $this->assertIsArray($p->getParameters());
        $this->assertIsString($p->getGroup());
        $this->assertIsString($p->getIcon());
        $this->assertIsString($p->getId());
        $this->assertIsString($p->getName());
        $this->assertIsString($p->getSvg());
        $this->assertIsString($p->getUrl());

        $this->assertEquals($group, $p->getGroup());
        $this->assertEquals($icon, $p->getIcon());
        $this->assertEquals($id, $p->getId());
        $this->assertEquals($providerName, $p->getName());
        $this->assertEquals($svg, $p->getSvg());
        $this->assertEquals($url, $p->getUrl());
        $this->assertArrayHasKey('param1', $p->getParameters());
        $this->assertArrayHasKey('param2', $p->getParameters());
        $this->assertContains(1, $p->getParameters());
        $this->assertContains(2, $p->getParameters());
    }
}