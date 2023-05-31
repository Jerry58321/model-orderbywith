<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Jerry58321\ModelOrderByWith\Supports\QueryAs;
use Models\User;

class OrderByWithTest extends TestCase
{
    /**
     * 測試正確性
     *
     * @dataProvider getOrderByWithTestCases
     * @return void
     */
    public function testOderByWithIsCorrect($withRelation, $column, $direction, string $expectSqlString)
    {
        $query = User::orderByWith($withRelation, DB::raw($column), $direction);

        $this->assertEquals($this->getSqlString($query), $expectSqlString);
    }

    /**
     * @param  Builder  $query
     * @return string
     */
    protected function getSqlString(Builder $query): string
    {
        $bindings = $query->getBindings();
        $sql = str_replace('?', '%s', $query->toSql());
        foreach ($bindings as $key => $value) {
            if (is_string($value)) {
                $bindings[$key] = "'{$value}'";
            }
        }
        return sprintf($sql, ...$bindings);
    }

    /**
     * @return array[]
     */
    public function getOrderByWithTestCases(): array
    {
        return [
            // 測試關聯 activity_log Table 升序排序
            [
                'activityLog',
                'id',
                'asc',
                "select * from `user` order by (select id from activity_log where `user`.`id` = `activity_log`.`user_id`) asc"
            ],
            // 測試關聯 activity_log Table 降序排序
            [
                'activityLog',
                'id',
                'desc',
                "select * from `user` order by (select id from activity_log where `user`.`id` = `activity_log`.`user_id`) desc"
            ],
            // 測試關聯對象加上where條件
            [
                ['activityLog' => function (QueryAs $query) {
                    return $query->where('created_at', '2022-01-01 00:00:00');
                }],
                'id',
                'asc',
                "select * from `user` order by (select id from activity_log where `user`.`id` = `activity_log`.`user_id` and `created_at` = '2022-01-01 00:00:00') asc"
            ],
            // 測試欄位使用聚合函式
            [
                'activityLog',
                'MAX(id)',
                'asc',
                "select * from `user` order by (select MAX(id) from activity_log where `user`.`id` = `activity_log`.`user_id`) asc"
            ],
            // 測試關聯的關聯
            [
                'activityLog.creditLog',
                'SUM(credit_log.amount)',
                'asc',
                "select * from `user` order by (select SUM(credit_log.amount) from activity_log,credit_log where `user`.`id` = `activity_log`.`user_id` and `activity_log`.`id` = `activity_log`.`credit_log_id`) asc"
            ],
            // 測試關聯的關聯使用where條件別名添加
            [
                ['activityLog.creditLog' => function (QueryAs $query) {
                    /** @var QueryAs|Builder $query */
                    return $query->where('id', '>=', 1)->prefixColumns(['id']);
                }],
                'SUM(credit_log.amount)',
                'asc',
                "select * from `user` order by (select SUM(credit_log.amount) from activity_log,credit_log where `user`.`id` = `activity_log`.`user_id` and `activity_log`.`id` = `activity_log`.`credit_log_id` and `credit_log`.`id` >= 1) asc"
            ]
        ];
    }
}