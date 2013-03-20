<?php
/**
 * @copyright 2012,2013 Binovo it Human Project, S.L.
 * @license http://www.opensource.org/licenses/bsd-license.php New-BSD
 */

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;
use Binovo\ElkarBackupBundle\Lib\Globals;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130306101349 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $maxId = 0;
        foreach (scandir(Globals::getUploadDir()) as $script) {
            $matches = array();
            if (preg_match('/^([0-9]{4})[.]script$/', $script, $matches)) {
                $maxId = max($maxId, (int)$matches[1]);
            }
        }
        $nextId = $maxId + 1;
        $description =<<<EOF
Use this script as pre client and post client script (yes, both) if you want to trigger snapshot creation on Windows machines.

In order for this script to work you will have to install the contents of the /usr/share/elkarbackup/extra/windows (this is the default path, might be some other in your system) in the Windows machine.

You will find more information regarding the Windows-side configuration of this feature in the aforementioned directory.
EOF;
        $this->addSql("INSERT INTO Script VALUES (?, ?, 'TriggerSnapshotGenerateOrDelete.sh', 1, 0, 1, 0, NULL)", array($nextId, $description));
        $this->abortIf(!copy("/usr/share/elkarbackup/extra/windows/TriggerSnapshotGenerateOrDelete.sh", Globals::getUploadDir() . "/" . sprintf("%04d.script", $nextId)));
        $this->abortIf(!chmod(Globals::getUploadDir() . "/" . sprintf("%04d.script", $nextId), 0755));
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

    }
}
