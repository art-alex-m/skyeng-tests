-- Написать SQL запрос, который выведет все пропуски
-- исходные данные
create table test (
  id int not null primary key
);

insert into test (id) values (1), (2), (3), (6), (8), (9), (12);

-- запрос выборки интервалов пропусков
select id as "FROM", next_id as "TO"
from (
  select id, lead(id) over(order by id) as next_id
  from test
) T
where id + 1 <> next_id;