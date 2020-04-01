<?php


namespace App\Providers\QuestionMark;

/**
 * Class QuestionMark
 * 用以使用原生Sql Query時, 產生QuestionMark 來防止Sql Injection
 * @package App\Providers\QuestionMark
 */
class QuestionMark
{
    /**
     * 依Data產生問號
     * @param $field //欄位名稱
     * @param $data
     * @return array
     */
    public function makeQM($field, $data)
    {
        $qm = [];
        for ($i = 0; $i < count($field); $i++) {
            $qm[] = '?';
        }
        $qmKey = '(' . join(',', $qm) . ')';

        $values = $qmValues = [];
        foreach ($data as $item) {
            $values = array_merge($values, array_values($item));
            $qmValues[] = $qmKey;
        }
        return [$qmValues, $values];
    }
}
