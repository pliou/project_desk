<?php

namespace Ppl\ProjectDesk\Helper;

use Ppl\ProjectDesk\Helper\AbstractDataHelper;

class BEUserDataHelper extends AbstractDataHelper
{
    const TABLE = 'be_user';
    public function getAllUser(): array
    {
        $data = [];
        $sql = '
            SELECT *
            FROM be_users 
            WHERE 
                deleted = 0 
                AND disable = 0
                AND username != "_cli_"
        ';
        $stmt = $this->getConnection()->executeQuery($sql);

        return $stmt->fetchAllAssociative();
    }
}
