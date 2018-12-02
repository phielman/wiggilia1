<?php
class model_random extends _Model
{
    protected $sql = array(
    	'select'=>'SELECT * FROM `people`',
    	'check' => 'SELECT * FROM `people` WHERE `id` = :id',
       	'selectSingle' => 'SELECT * FROM `people` WHERE `chosen` = 0 AND `id` != :id ORDER BY RAND() LIMIT 1',
       	'setWho' => 'UPDATE `people` SET `chosen` = :id WHERE `id` = :rand_id',
       	'setRolled' => 'UPDATE `people` SET `set` = 1 WHERE `id` = :roller_id',

    );


    function wybierz_osobe($my_id)
    {

        if($my_id !== 'select')
        {
        	$check = $this->execute('check', array('id' => $my_id));
            //sprawdzamy czy osoba o ID $my_id już losowała.
            $result = array();
        	if($check[0]->set == 0)
            {
            	$osoba = $this->execute('selectSingle', array('id' => $my_id));
                // zapytanie losuje dowolną osobę, która nie została wybrana i nie ma id $my_id
            	$who = $this->execute('setWho', array('id' => $my_id, 'rand_id' => $osoba[0]->id));
                // zapytanie dodaje info w polu osoby wylosowanej o ID osoby która ją wylosowała
           		$setTaken = $this->execute('setRolled', array('roller_id' => $my_id));
                // zapytanie aktualizuje nas jako osoby które już losowały

                if($osoba && $who && $setTaken)
                {
                    $result[] = 'Elfy przekazują doskonałe informacje! W tym roku Twój prezent dostanie <strong>'.$osoba[0]->name . '</strong>.';
                }

                else
                    $result[] = 'Wygląda na błąd połączenia z serwerami Mikołaja. Spróbuj ponownie później';
            }

            else $result[] = 'Chaos u św. Mikołaja! Wygląda na to, że kontaktujesz się z nami drugi raz!';
        }
        else
            $result[] = 'Elfy nie mogą Ci pomóc, jeśli nie wiedzą kim jesteś! Wybierz swoje imię!';
        return $result;
    }

}
?>