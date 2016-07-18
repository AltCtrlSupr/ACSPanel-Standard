<?php
namespace ACS\ACSPanelBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use ACS\ACSPanelBundle\Entity\ServiceType;
use ACS\ACSPanelBundle\Entity\FieldType;

class LoadServiceTypeData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Database types
        $db_type = new ServiceType();
        $db_type->setName('DB');

        $manager->persist($db_type);
        $manager->flush();

        $mysql_type = new ServiceType();
        $mysql_type->setName('MySQL');
        $mysql_type->setParentType($db_type);

        $mysql_user_field = new FieldType();
        $mysql_user_field->setSettingKey('admin_user');
        $mysql_user_field->setType('text');
        $mysql_user_field->setLabel('MySQL Admin User');
        $mysql_user_field->setContext('MySQL Settings');
        $mysql_user_field->setDefaultValue('root');

        $mysql_password_field = new FieldType();
        $mysql_password_field->setSettingKey('admin_password');
        $mysql_password_field->setType('text');
        $mysql_password_field->setLabel('MySQL Admin User Password');
        $mysql_password_field->setContext('MySQL Settings');
        $mysql_password_field->setDefaultValue('password');

        $mysql_type->addFieldType($mysql_user_field);
        $mysql_type->addFieldType($mysql_password_field);

        $manager->persist($mysql_type);
        $manager->flush();

        // HTTP Types
        $http_type = new ServiceType();
        $http_type->setName('HTTP');
        $manager->persist($http_type);
        $manager->flush();

        $apache2_type = new ServiceType();
        $apache2_type->setName('Apache2');
        $apache2_type->setParentType($http_type);
        $manager->persist($apache2_type);
        $manager->flush();

        $apache2wc_type = new ServiceType();
        $apache2wc_type->setName('Apache2 Webcache');
        $apache2wc_type->setParentType($http_type);
        $manager->persist($apache2wc_type);
        $manager->flush();

        // DNS Types
        $dns_type = new ServiceType();
        $dns_type->setName('DNS');
        $manager->persist($dns_type);
        $manager->flush();

        // FTP Types
        $ftp_type = new ServiceType();
        $ftp_type->setName('FTP');
        $manager->persist($ftp_type);
        $manager->flush();

        $pureftp = new ServiceType();
        $pureftp->setName('PureFTPd');
        $pureftp->setParentType($ftp_type);
        $manager->persist($pureftp);
        $manager->flush();

        $proftp = new ServiceType();
        $proftp->setName('ProFTPd');
        $proftp->setParentType($ftp_type);
        $manager->persist($proftp);
        $manager->flush();

        // Mail types
        $mail_type = new ServiceType();
        $mail_type->setName('Mail');
        $manager->persist($mail_type);
        $manager->flush();

        $dovecot_type = new ServiceType();
        $dovecot_type->setName('Dovecot (IMAP/POP3)');
        $dovecot_type->setParentType($mail_type);
        $manager->persist($dovecot_type);
        $manager->flush();

        $postfix_type = new ServiceType();
        $postfix_type->setName('Postfix (SMTP)');
        $postfix_type->setParentType($mail_type);
        $manager->persist($postfix_type);
        $manager->flush();
    }
}

