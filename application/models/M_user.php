<?php
class M_User extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }
  function get_level() {
    return $this->db->query("SELECT * FROM tbl_level where id != '1'")->result();
  }

  function get_user()  {
    return $this->db->query("SELECT
      tu.id,
      tu.nama_user,
      tu.username,
      tl.level_name,
      tu.status
    from
      tbl_user tu
    left join tbl_level tl on
      tl.id = tu.level
    where tu.level != '1'")->result();
  }
}