<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Doctrine\EntityManagerAwareInterface;
use App\Doctrine\EntityManagerAwareTrait;
use App\Entity\Employee;
use App\Entity\EmployeeGroup;
use App\Entity\Translations\EmployeeGroupTranslation;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;
use Symfony\Component\Yaml\Yaml;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210504152021 extends AbstractMigration implements EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;

    private const STAFF_GROUPS = __DIR__.'/data/2021-staff/staff_groups.yaml';
    private const STAFF_DATA = __DIR__.'/data/2021-staff/staff.csv';

    public function getDescription() : string
    {
        return 'Insert groups';
    }

    public function up(Schema $schema) : void
    {
        $this->insertGroups();
    }

    public function insertGroups()
    {
        $staffGroups = Yaml::parse(file_get_contents(self::STAFF_GROUPS));

        foreach ($staffGroups as $slug => $data) {
            $grp = new EmployeeGroup();
            $grp->setTitle($data['uk']);
            $grp->setSlug($slug);
            $grp->setCreatedBy('MIGRATION');

            $translation = new EmployeeGroupTranslation();
            $translation->setLocale('en');
            $translation->setField('title');
            $translation->setObject($grp);
            $translation->setContent($data['en']);

            $this->em->persist($grp);
            $this->em->persist($translation);

            $this->updateEmployeesGroup($grp);
        }

        $this->em->flush();
    }

    private function updateEmployeesGroup(EmployeeGroup $grp)
    {
        $staff = array_map('str_getcsv', file(self::STAFF_DATA));
        array_shift($staff); // remove headers

        foreach ($staff as $performance) {
            $name = array_shift($performance);
            $link = array_shift($performance);
            $slug = array_shift($performance);
            $position = array_shift($performance);
            $groupTitle = array_shift($performance);

            if ($grp->getSlug() !== $groupTitle) {
                continue;
            }

            /** @var Employee $employee */
            $employee = $this->em
                ->getRepository(Employee::class)
                ->findOneBy(['slug' => $slug])
                ->setUpdatedBy('MIGRATION');

            if (!$employee) {
                throw new \RuntimeException(sprintf('There is no employee with "%s" slug', $slug));
            }

            $employee->setEmployeeGroup($grp);
        }
    }

    public function down(Schema $schema) : void
    {
    }
}
