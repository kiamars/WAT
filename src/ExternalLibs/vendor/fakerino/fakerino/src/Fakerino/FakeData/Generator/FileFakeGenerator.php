<?php
/**
 * This file is part of the Fakerino package.
 *
 * (c) Nicola Pietroluongo <nik.longstone@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fakerino\FakeData\Generator;

use Fakerino\DataSource\FakeFileContainer;
use Fakerino\FakeData\AbstractFakeDataGenerator;

/**
 * Class FileFakeGenerator,
 * gets fake data from a file.
 *
 * @author Nicola Pietroluongo <nik.longstone@gmail.com>
 */
final class FileFakeGenerator extends AbstractFakeDataGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        if ($fakeFile = FakeFileContainer::get($this->caller->getOption('filename'))) {
            $lines = file($fakeFile);
            $index = mt_rand(0, count($lines) - 1);
            $element = $lines[$index];

            return preg_replace("/\r|\n/", "", $element);
        } else {

            return;
        }
    }
}