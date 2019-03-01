<html>
<head>
  <title>New User Creation</title>
</head>
<body>
  <pre><code>


<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 1-3-2019
 * Time: 10:26
 */

// set some constants
define("LDAP_HOST","localhost");
define("LDAP_PORT",389);
define("LDAP_ADMIN_CN","cn=webuserldap,ou=users,ou=samenfit,dc=samenfit,dc=local");
define("LDAP_PASSWORD","wachtwoord");

// connect to the service
$lnk = ldap_connect(LDAP_HOST, LDAP_PORT);

// check connectivity
if ($lnk === false) {
    throw(new Exception("Cannot connect to " . $host . ":" . $port));
    die();
}
else{
    // expect protocol version 3 to be the standard
    ldap_set_option($lnk, LDAP_OPT_PROTOCOL_VERSION, 3);

    // bind to the service using a username & password
    $bindres = ldap_bind($lnk, LDAP_ADMIN_CN,LDAP_PASSWORD);
    if ($bindres === false) {
        throw(new Exception("Cannot bind using user " . LDAP_ADMIN_CN));
    }
}

//FIXME: need to do a lot of security checks here!
$username  = $_POST['username'];
$sn        = $_POST['achternaam'];
$givenName = $_POST['voornaam'];

// setup some compound variables based upon the input
$cn = $sn . " " . $givenName;
$dn = "cn=" . $cn . ",ou=users,ou=samenfit,dc=samenfit,dc=local";

// setup an array with all the attributes needed to add a new user.
$fields=Array();

// first indicate what kind of object we want te create ("Objectclass"). Multivalue attribute!!
$fields['objectClass'][]= "top";
$fields['objectClass'][]= "inetOrgPerson";
$fields['objectClass'][]= "person";
$fields['objectClass'][]= "organizationalPerson";

$fields['cn']       = $cn;
$fields['sn']       = $sn;
$fields['uid']      = $username;
$fields['givenName'] = $givenName;

echo "De gebruiker wordt aangemaakt op $dn \n";

// Now do the actual adding of the object to the LDAP-service
$addResult = ldap_add($lnk, $dn, $fields);

// check result
if ($addResult === true){
    echo "Gebruiker toegevoegd!\n";

    // now get the object from the database and check the values.
    $ldapRes = ldap_read($lnk, $dn, "(ObjectClass=*)", array("*"));

    if ($ldapRes !== false ) {
        $entries = ldap_get_entries($lnk, $ldapRes);
        /*
         * De entries die teruggeven worden hebben
         *  - óf een index met een getal om attribuut-namen terug te geven
         *  - óf een index met een string om de waarde(n) van een attribuut terug te geven.
         */

        if ($entries['count'] == 1){
            // take the first entry and check the 'count'-attribute
            $entry = $entries[0];
            $numAttrs = $entry['count'];

            // collect all the attribute names
            $attributesReturned = array();
            for($i=0;$i<$numAttrs;$i++){
                $attr = strtolower($entry[$i]);
                $attributesReturned[$attr] = $attr;
            }//for each attribute number

            // Now get the attribute values
            $valuesNamed = array();
            foreach($attributesReturned as $attributeName){
                // check if a value is an Array or a single value
                if (is_array($entry[$attributeName])){
                    $thisItem = $entry[$attributeName];

                    //remove the 'count'-attribute from the array and glue them together.
                    unset($entry[$attributeName]['count']);
                    $valuesNamed[$attributeName] = join( "/", $entry[$attributeName] );
                }
                else{
                    $valuesNamed[$attributeName] = $entry[$attributeName];
                }
            }//for each attribute

            // Now show all the values
            foreach($valuesNamed as $key => $value){
                echo "{$key} = $value \n";
            }//for each value

        }// if exactly one item found (this must be!)
    }
    else{
        throw new Exception("UpdateLDAPContactFromGoogleContact::Cannot perform query for this user $contactDN");
    }
}//if successfully added a new user
else{
    echo "Error : createNewUser :: " . ldap_error($lnk);
}


?>
        </code>
      </pre>
    </body>
</html>

