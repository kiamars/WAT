<?php
/**
 * This file is part of the Fakerino package.
 *
 * (c) Nicola Pietroluongo <nik.longstone@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fakerino\Test\Configuration\ConfigurationFile;

use Fakerino\Configuration\ConfigurationFile\YmlConfigurationFile;
use Fakerino\DataSource\File\File;

class YmlConfigurationFileTest extends \PHPUnit_Framework_TestCase
{
    public function testYamlConfFile()
    {
        $fileDir = __DIR__ . '/../../Fixtures/';
        $ymlFilePath = $fileDir . 'file.yml';
        $confFile = new File($ymlFilePath);
        $ymlConf = new YmlConfigurationFile($confFile);
        $result = $ymlConf->toArray();

        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }

    public function testEmptyYamlConfFile()
    {
        $fileDir = __DIR__ . '/../../Fixtures/';
        $ymlFilePath = $fileDir . 'emptyFile.ini';
        $confFile = new File($ymlFilePath);
        $ymlConf = new YmlConfigurationFile($confFile);
        $result = $ymlConf->toArray();

        $this->assertInternalType('array', $result);
        $this->assertEmpty($result);
    }

    public function testYamlFileParseThatReturnAString()
    {
        $fileDir = __DIR__ . '/../../Fixtures/';
        $ymlFilePath = $fileDir . 'fileOneLine.yml';
        $confFile = new File($ymlFilePath);
        $ymlConf = new YmlConfigurationFile($confFile);
        $result = $ymlConf->toArray();

        $this->assertInternalType('array', $result);
    }
}