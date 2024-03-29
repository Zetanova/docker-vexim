<?php
  /* SQL Database login information */
  include_once dirname(__FILE__) . "/i18n.php";

  $sqlserver = getenv("VEXIM_SQL_SERVER");
  $sqltype = getenv("VEXIM_SQL_TYPE");
  $sqldb = getenv("VEXIM_SQL_DB");
  $sqluser = getenv("VEXIM_SQL_USER");
  $sqlpass = getenv("VEXIM_SQL_PASS");

  $dsn = "$sqltype:host=$sqlserver;dbname=$sqldb";
  $dboptions = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');

  try {
    $dbh = new PDO($dsn, $sqluser, $sqlpass, $dboptions);
    $dbh->setAttribute($dbh::ATTR_DEFAULT_FETCH_MODE, $dbh::FETCH_ASSOC);
  } catch (PDOException $e) {
    die($e->getMessage());
  }

  /* We use this IMAP server to check user quotas */
  $imapquotaserver = "{mail.CHANGE.com:143/imap/notls}";
  $imap_to_check_quota = "no";

  /* Setting this to 0 if only admins should be allowed to login */
  $AllowUserLogin = 1;

  /* Choose whether to break up domain and user lists alphabetically */
  $alphadomains = 1;
  $alphausers = 1;

  /* Set to either "sha512" or "bcrypt" (only on *BSD) for advanced
     pw-hash functions, "des" and "md5" (kept for compatibility
     to older setups), or "clear" for clear-text passwords.
     It is not recommended to use the "clear" option 
     Alternatively, you can specify custom salt prefix here, e.g.
     SHA-512 with 10000 rounds -> $cryptscheme='$6$rounds=10000$'
     or bcrypt with complexity 2^12 -> $cryptscheme='$2a$12$' */
  $cryptscheme = getenv("VEXIM_CRYPTSCHEME");

  /* Guess domain name from hostname and allow login based on
     local part only. It is off by default, set this value to 1
     in order to enable this function */
  $domainguess = 0;

  /* Enable password strength check
     To disable this check set $passwordstrengthcheck = 0;
  */
  $passwordstrengthcheck = 1;

  /* The UID's and GID's control the default UID and GID for new domains
     and if postmasters can define their own.
     THE UID AND GID MUST BE NUMERIC! */
  $uid = intval(getenv("VEXIM_UID"));
  $gid = intval(getenv("VEXIM_GID"));
  $postmasteruidgid = "yes";

  /* The location of your mailstore for new domains.
     Make sure the directory belongs to the configured $uid/$gid! */
  $mailroot = getenv("VEXIM_MAILROOT");

  /* Check if mail store specified above exists when creating a new domain.
     Generally, this shouldn't be disabled, but there may be special cases
     when our check doesn't work and should be disabled (e.g. the parent
     directory of mail store is inaccessible to your web server). */
  $testmailroot = true;

  /* path to Mailman */
  $mailmanroot = "http://www.EXAMPLE.com/mailman";

  /* sa_tag is the default value to offer when we create new domains for SpamAssassin tagging
     sa_refuse is the default value to offer when we create new domains for SpamAssassin dropping */
  $sa_tag = "2";
  $sa_refuse = "5";

  /* max size of a vacation message */
  $max_vacation_length = 1023;

  /* Welcome message, sent to new POP/IMAP accounts */
  @$welcome_message = "Welcome, {$_POST['realname']} !\n\n"
		   . "Your new E-mail account is all ready for you.\n\n"
		   . "Here are some settings you might find useful:\n\n"
		   . "Username: {$_POST['localpart']}@{$_SESSION['domain']}\n"
		   . "POP3 server: mail.{$_SESSION['domain']}\n"
		   . "SMTP server: mail.{$_SESSION['domain']}\n";

  /* Welcome message, sent to new domains */
  @$welcome_newdomain = "Welcome, and thank you for registering your e-mail domain\n"
		     . "{$_POST['domain']} with us.\n\nIf you have any questions, please\n"
		     . "don't hesitate to ask your account representitive.\n";
?>
