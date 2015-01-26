<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Hautelook\AliceBundle\Alice\DataFixtureLoader;
use Nelmio\Alice\Fixtures;

class LoadData extends DataFixtureLoader
{
    public function load(ObjectManager $om)
    {
        Fixtures::load(__DIR__ . '/fixtures.yml', $om, array('providers' => array($this)));
    }

    public function mediaTypeName()
    {
        $names = array(
            'Videofile',
            'Audiofile',
            'Photo',
            'Whatsoever',
        );
        return $names[array_rand($names)];
    }

    public function roleName()
    {
        $names = array(
            'Третье дерево справа',
            'Четвертый кролик слева',
            'Лично Виталий Мушкин',
            'Отелло',
            'Кушать подано',
            'Гамлет',
            'Фрау Меркель',
            'Герр Меркель',
        );
        return $names[array_rand($names)];
    }

    public function positionName()
    {
        $names = array(
            'директор',
            'заместитель директора по лепке вареников',
            'главная актриса',
            'осветитель',
            'закрыватель кулис',
            'открыватель оркестровой ямы',
        );
        return $names[array_rand($names)];
    }

    public function performanceName()
    {
        $names = array(
            'Сильва',
            'Не Сильва',
            'А есть что-то кроме Сильвы?',
        );
        return $names[array_rand($names)];
    }

    protected function getFixtures()
    {
        return array(
            __DIR__ . '/fixtures.yml',
        );
    }
}