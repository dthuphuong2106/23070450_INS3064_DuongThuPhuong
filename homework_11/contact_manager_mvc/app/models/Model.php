
<?php

namespace App\Models;

use App\Core\Database;
use PDO;

abstract class Model
{
    // Protected PDO connection for child models
    protected PDO $db;

    // Receive database connection
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
}
