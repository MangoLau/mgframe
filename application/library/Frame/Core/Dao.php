<?php
/**
 * @author MangoLau
 */

namespace Frame\Core;


class Dao
{

    protected $dbalias = '';
    protected $table = '';
    protected $params = [];
    protected $sql = '';
    protected $limit = '';
    protected $where = '';
    protected $orderby = '';
    protected $groupby = '';
    protected $use_index = '';
    protected $join = '';
    protected $for_update = '';

    /**
     * Dao constructor.
     * @param string $table
     * @param string $dbalias
     */
    public function __construct($table = '', $dbalias = '')
    {
        if (empty($table)) {
            $table = strtolower(substr(get_class($this), 0, -5));
        }
        $this->table = $table;
        if (!empty($dbalias)) {
            $this->dbalias = $dbalias;
        }
        if (empty($this->dbalias)) {
            $this->dbalias = 'default';
        }
    }

    /**
     * reset
     */
    public function reset()
    {
        $this->params = [];
        $this->sql = '';
        $this->limit = '';
        $this->where = '';
        $this->orderby = '';
        $this->groupby = '';
        $this->use_index = '';
        $this->join = '';
        $this->for_update = '';
    }

    /**
     * set table name
     * @param string $table
     * @return $this
     */
    public function table(string $table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * fields
     * @param string|array $fields
     * @return $this
     */
    public function fields($fields = '*')
    {
        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }
        $this->sql = "SELECT {$fields} FROM `{$this->table}`";
        return $this;
    }

    /**
     * where
     * @return $this
     */
    public function where()
    {
        $argc = func_num_args();
        $args = func_get_args();
        $this->parseWhere($argc, $args);
        return $this;
    }

    /**
     * or where
     * @return $this
     */
    public function orWhere()
    {
        $argc = func_num_args();
        $args = func_get_args();
        $this->parseWhere($argc, $args, 'OR');
        return $this;
    }

    /**
     * parse where
     * @param $argc
     * @param $args
     * @param string $logic
     */
    public function parseWhere($argc, $args, $logic = 'AND')
    {
        $where = '';
        if ($argc == 0) {
            return;
        } elseif ($argc == 1) {
            if (!is_array($args[0])) return;
            foreach ($args[0] as $k => $v) {
                $where = "`{$k}`=? {$logic} ";
                $this->params[] = $v;
            }
            $where = rtrim($where, " {$logic} ");
        } elseif ($argc == 2) {
            list($field, $value) = $args;
            $where = "`{$field}`=?";
            $this->params[] = $value;
        } elseif ($argc == 3) {
            list($field, $exp, $value) = $args;
            $exp = strtoupper($exp);
            switch ($exp) {
                case 'IN':
                case 'NOT IN':
                    if (!is_array($value) || empty($value)) break;
                    $alts = rtrim(str_repeat('?,', count($value)), ',');
                    $where = "`{$field}` {$exp} ($alts)";
                    $this->params = array_merge($this->params, $value);
                    break;
                case 'BETWEEN':
                case 'NOT BETWEEN':
                    if (!is_array($value) || count($value) != 2) break;
                    $where = "`{$field}` {$exp} ? AND ?";
                    array_push($this->params, min($value), max($value));
                    break;
                default:
                    $where = "`{$field}` {$exp} ?";
                    $this->params[] = $value;
                    break;
            }
        }
        if ($where == '') {
            return;
        }
        if ($this->where == '') {
            $this->where = " WHERE {$where}";
        } else {
            $this->where .= " {$logic} {$where}";
        }
    }

    /**
     * join
     * @param string $table
     * @param string $on
     * @return $this
     */
    public function join(string $table, string $on)
    {
        $this->join .= " INTER JOIN `{$table}` ON ({$on})";
        return $this;
    }

    /**
     * right join
     * @param string $table
     * @param string $on
     * @return $this
     */
    public function rightJoin(string $table, string $on)
    {
        $this->join .= " RIGHT JOIN `{$table}` ON ({$on})";
        return $this;
    }

    /**
     * left join
     * @param string $table
     * @param string $on
     * @return $this
     */
    public function leftJoin(string $table, string $on)
    {
        $this->join .= " LEFT JOIN `{$table}` ON ({$on})";
        return $this;
    }

    /**
     * use index
     * @param string $field
     * @return $this
     */
    public function useIndex(string $field)
    {
        $this->use_index = " USE INDEX({$field})";
        return $this;
    }

    /**
     * group by
     * @param string $group
     * @return $this
     */
    public function groupby(string $group)
    {
        if (!empty($group)) {
            $this->groupby = " GROUP BY {$group}";
        } else {
            $this->groupby = '';
        }
        return $this;
    }

    /**
     * order by
     * @param string $order
     * @return $this
     */
    public function orderby(string $order)
    {
        if (!empty($order)) {
            $this->orderby = " ORDER BY {$order}";
        } else {
            $this->orderby = '';
        }
        return $this;
    }

    /**
     * limit
     * @return $this
     */
    public function limit()
    {
        $argc = func_num_args();
        $args = func_get_args();
        if ($argc == 2) {
            $offset = intval($args[0]);
            $limit = intval($args[1]);
            $this->limit = " LIMIT {$offset},{$limit}";
        } elseif ($argc == 1) {
            $size = intval($args[0]);
            $this->limit = " LIMIT {$size}";
        } else {
            $this->limit = '';
        }
        return $this;
    }

    /**
     * for update
     * @return $this
     */
    public function lock()
    {
        $this->for_update = " FOR UPDATE";
        return $this;
    }

    /**
     * select
     * @param int $mode
     * @return mixed
     * @throws \Exception
     */
    public function select(int $mode = Db::FETCH_ALL)
    {
        if (empty($this->sql)) {
            $this->fields();
        }
        return $this->run($mode);
    }

    /**
     * get one
     * @return mixed
     * @throws \Exception
     */
    public function getOne()
    {
        return $this->select(Db::FETCH_ROW);
    }

    /**
     * insert
     * @param array $data
     * @param bool $replace
     * @param bool $ignore
     * @return bool|mixed
     * @throws \Exception
     */
    public function insert(array $data = [], bool $replace = false, bool $ignore = false)
    {
        $this->reset();
        if (empty($data)) {
            return false;
        }
        $alts = [];
        if (!empty($data[0]) && is_array($data[0])) {
            $fields = '`' . implode('`,`', array_keys($data[0])) . '`';
            foreach ($data as $k => $v) {
                $alts[] = '(' . implode(',', array_fill(0, count($v), '?')) . ')';
                $this->params = array_merge($this->params, array_values($v));
            }
            $alts = implode(',', $alts);
        } else {
            $fields = '`' . implode('`,`', array_keys($data)) . '`';
            $alts = '(' . implode(',', array_fill(0, count($data), '?')) . ')';
            $this->params = array_merge($this->params, array_values($data));
        }
        $verb = $replace ? 'REPLACE' : ($ignore ? 'INSERT IGNORE' : 'INSERT');
        $this->sql = "{$verb} INTO `{$this->table}` ({$fields}) VALUES {$alts}";
        return $this->run(0);
    }

    /**
     * replace
     * @param array $data
     * @return bool|mixed
     * @throws \Exception
     */
    public function replace(array $data = [])
    {
        return $this->insert($data, true);
    }

    /**
     * update
     * @param array $data
     * @return $this|mixed
     * @throws \Exception
     */
    public function update(array $data = [])
    {
        if (empty($data)) {
            return $this;
        }
        $sets = '';
        $params = [];
        foreach ($data as $k => $v) {
            if (!empty($v['exp'])) {
                $sets .= "`{$k}`={$v['exp']},";
                continue;
            }
            $sets .= "`{$k}`=?,";
            $params[] = $v;
        }
        $sets = rtrim($sets, ',');
        $this->params = array_merge($params, $this->params);
        $this->sql = "UPDATE `{$this->table}` SET {$sets}";
        return $this->run(0);
    }

    /**
     * delete
     * @return mixed
     * @throws \Exception
     */
    public function delete()
    {
        $this->sql = "DELETE FROM `{$this->table}`";
        return $this->run(0);
    }

    /**
     * count
     * @param int|string $field
     * @return mixed
     * @throws \Exception
     */
    public function count($field = 1)
    {
        if ($field != 1) {
            $field = "`{$field}`";
        }
        $this->sql = "SELECT COUNT({$field}) FROM `{$this->table}`";
        return $this->run(Db::FETCH_COL);
    }

    /**
     * exists
     * @return bool
     * @throws \Exception
     */
    public function exists()
    {
        $this->sql = "SELECT 1 FROM `{$this->table}`";
        return $this->run(Db::FETCH_COL) == 1;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        $this->sql = implode('', array(
            $this->sql,
            $this->join,
            $this->use_index,
            $this->where,
            $this->groupby,
            $this->orderby,
            $this->limit,
            $this->for_update
        ));
        return $this->sql;
    }

    /**
     * get last insert id
     * @return mixed
     */
    public function getLastInsertId()
    {
        return MG::db($this->dbalias)->getLastInsertId();
    }

    /**
     * get affected rows
     * @return mixed
     */
    public function getAffectedRows()
    {
        return MG::db($this->dbalias)->getAffectedRows();
    }

    /**
     * begin transaction
     * @return mixed
     */
    public function beginTrans()
    {
        return MG::db($this->dbalias)->beginTrans();
    }

    /**
     * commit
     * @return mixed
     */
    public function commit()
    {
        return MG::db($this->dbalias)->commit();
    }

    /**
     * roll back
     * @return mixed
     */
    public function rollBack()
    {
        return MG::db($this->dbalias)->rollBack();
    }

    /**
     * run
     * @param int $mode
     * @param int $col
     * @return mixed
     * @throws \Exception
     */
    public function run($mode = 0, $col = 0)
    {
        if (empty($this->sql)) {
            throw new \Exception("DAO::run()sql:ERROR.");
        }
        $sql = $this->getSql();
        $ret = MG::db($this->dbalias)->query($sql, $this->params, $mode, $col);
        $this->reset();
        return $ret;
    }

    /**
     * query
     * @param string $sql
     * @param array $params
     * @param int $mode
     * @param int $col
     * @return mixed
     */
    public function query(string $sql, array $params = [], int $mode = 0, int $col = 0)
    {
        return MG::db($this->dbalias)->query($sql, $params, $mode, $col);
    }
}