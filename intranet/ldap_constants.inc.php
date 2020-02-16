<?php
// set some constants
define("BASE_DN", "ou=mijnsite,dc=mijnsite,dc=local");
define("LDAP_HOST","192.168.123.149");
define("LDAP_PORT",389);
define("LDAP_ADMIN_CN","cn=webuserldap,ou=users,". BASE_DN);
define("LDAP_PASSWORD","wachtwoord"); // FIXME: Investigate how to prevent plaintext passwords.
