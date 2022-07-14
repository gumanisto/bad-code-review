<?php

// Let's assume this is the code in the controller. List everything that you think is not correct.
class Contrloller
{
    /**
     * @param $name
     * @param $amount
     * @param $type
     * @return integer
     */
    public function addMoney($name, $amount, $type)
    {
        $checkExist = $this->checkUserMoney($name);
        $checkExist = ($checkExist != '<b>(Ico)</b> <h11 style=\"color: red\">Произошла ошибка!</h11> <br/>') ? true : false;
        $name_uuid = $this->genUUID($name);

        if ($this->version == '1.12.2' and $this->plugin != 'iconomy') {
            if ($checkExist) {
                if ($type == 'add') {
                    $queryText = ($this->plugin == 'economylite') ? "UPDATE `economyliteplayers` SET `balance` = `balance` + '$amount' WHERE `uuid` = '$name_uuid' AND `currency` = 'economylite:coin'" : "UPDATE `{$this->table}` SET `money` = `money` + '$amount' WHERE `player_name` = '$name'";
                } else {
                    $queryText = ($this->plugin == 'economylite') ? "UPDATE `economyliteplayers` SET `balance` = '$amount' WHERE `uuid` = '$name_uuid' AND `currency` = 'economylite:coin'" : "UPDATE `{$this->table}` SET `money` = '$amount' WHERE `player_name` = '$name'";
                }
            } else {
                $queryText = ($this->plugin == 'economylite') ? "INSERT INTO `economyliteplayers` (`uuid`, `balance`, `currency`) VALUES ('$name_uuid', '$amount', 'economylite:coin')" : "INSERT INTO `{$this->table}` (`player_uuid`, `player_name`, `money`, `sync_complete`, `last_seen`) VALUES ('$name_uuid', '$name', '$amount', 'true', '0')";
            }
        } else {
            if ($checkExist) {
                $queryText = ($type == 'add') ? "UPDATE `{$this->table}` SET `balance` = `balance` + $amount WHERE `username` = '$name'" : "UPDATE `{$this->table}` SET `balance` = $amount WHERE `username` = '$name'";
            } else {
                $queryText = "INSERT INTO `{$this->table}` (`username`, `balance`) VALUES ('$name', $amount)";
            }
        }
        echo $queryText;
        $data = siteQuery($queryText, 'query', $this->mysqli);
        $text = ($data != null) ? "<b>(Ico)</b> <h11 style=\"color: green\">Игроку $name успешно начисленно: $amount эмеральдов!</h11> <br/>" : '<b>(Ico)</b> <h11 style="color: red">Произошла ошибка!</h11> <br/>';

        return $text;
    }

    /**
     * @param $name
     * @param $amount
     * @param $type
     * @return integer
     */
    public function checkMoney($name)
    {
        $name_uuid = $this->genUUID($name);

        if ($this->version == '1.12.2' AND $this->plugin != 'iconomy')
        {
            $queryText = ($this->plugin == 'economylite') ? "SELECT `balance` FROM `economyliteplayers` WHERE `uuid` = '{$name_uuid}' AND `currency` = 'economylite:coin'"
                : "SELECT `money` as 'balance' FROM `{$this->table}` WHERE `player_name` = '{$name}'";
        } else
        {
            $queryText = "SELECT `balance` FROM `{$this->table}` WHERE `username` = '{$name}'";
        }

        $data = siteQuery($queryText, 'assoc', $this->mysqli);
        $text = ($data != NULL) ? "<b>(Ico)</b> <h11 style=\"color: green\">Балланс игрока $name: {$data['balance']} эмеральдов!</h11> <br/>"
            : '<b>(Ico)</b> <h11 style=\"color: red\">Произошла ошибка!</h11> <br/>';

        return $text;
    }
}