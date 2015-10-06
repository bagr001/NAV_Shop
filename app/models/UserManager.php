<?php

namespace App\Model;

use Nette;
use Nette\Security\Passwords;

/**
 * Users management.
 */
class UserManager extends Nette\Object implements Nette\Security\IAuthenticator {

	const
			TABLE_NAME = 'customers',
			COLUMN_ID = 'no',
			COLUMN_EMAIL = 'email',
			COLUMN_PASSWORD_HASH = 'password',
			COLUMN_ROLE = 'role';

	/**
	 *
	 * @var Nette\Database\Context
	 */
	private $db;

	/**
	 *
	 * @var NAV\Customer\CustomerService
	 */
	private $cs;

	public function __construct(Nette\Database\Context $db, NAV\Customer\CustomerService $cs)
	{
		$this->db = $db;
		$this->cs = $cs;
	}

	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;

		$row = $this->db->table(self::TABLE_NAME)->select('*')->where(self::COLUMN_EMAIL, $email)->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The email is incorrect.', self::IDENTITY_NOT_FOUND);
		} elseif (!Passwords::verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		} elseif (Passwords::needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
			$row->update(array(
				self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
			));
		}

		$arr = $row->toArray();
		unset($arr[self::COLUMN_PASSWORD_HASH]);
//		return new Nette\Security\Identity($row[self::COLUMN_ID], $row[self::COLUMN_ROLE], $arr);
		return new Nette\Security\Identity($row[self::COLUMN_ID], 'customer', $arr);
	}

	/**
	 * Adds new user.
	 * @param  string
	 * @param  string
	 * @return void
	 */
	public function add($email, $password, $name)
	{

		$row = $this->db->table(self::TABLE_NAME)->where(self::COLUMN_EMAIL, $email)->fetch();

		if ($row) {
			throw new DuplicateEmailException;
		} else {

			$c = new NAV\Customer\Customer();
			$c->E_Mail = $email;
			$c->Name = $name;

			$cc = $this->cs->Create($c);

			if ($cc) {
				$this->db->table(self::TABLE_NAME)->insert(array(
					self::COLUMN_ID => $cc->{NAV\Customer\CustomerFields::NO},
					self::COLUMN_EMAIL => $email,
					self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
				));
			}
			return $cc;
		}
	}

}

class DuplicateEmailException extends \Exception {

}
