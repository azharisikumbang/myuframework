<?php
namespace Myu\App\Models;

use Myu\Components\Database\Model;

/**
 * Obat
 */
class Obat extends Model
{
	protected $table = "obat";

	public function get()
	{
		return $this->db->all();
	}
}