-- Спроектировать схему БД для хранения библиотеки. Интересуют авторы и книги.
-- справочник книг
create table books
(
  id serial not null
    constraint books_pkey
    primary key,
  title text
);

-- справочник авторов
create table authors
(
  id serial not null
    constraint authors_pkey
    primary key,
  name text
);

-- отношение книг к их авторам
create table books_to_authors
(
  id serial not null
    constraint books_to_authors_pkey
    primary key,
  book_id integer not null
    constraint books_id_fk
    references books
    on delete cascade,
  author_id integer not null
    constraint authors_id_fk
    references authors
    on delete cascade
);

create index books_to_authors_authorsid_index
on books_to_authors (author_id);

create index books_to_authors_books_index
on books_to_authors (book_id);

-- отношение книг к количеству авторов
create view books_authors_count as
select b.book_id, count(b.author_id) as authors_count
from books_to_authors as b
group by (b.book_id);

-- данные для справочника книг
insert into books (title) values ('B1'), ('B2'), ('B3'), ('B4');
-- данные для справочника авторов
insert into authors (name) values ('A1'), ('A2'), ('A3'), ('A4');
-- данные для отношения книг к авторам
insert into books_to_authors (book_id, author_id) values
  (1, 1),
  (1, 2),
  (1, 3),
  (2, 1),
  (2, 3),
  (3, 3),
  (4, 3),
  (4, 2),
  (4, 4),
  (4, 1);

-- запрос выборки данных наименование книги и количесво авторов равное 3
select concat_ws(' ', b.title, '-', c.authors_count) as template
from books_authors_count c
left join books b on c.book_id = b.id
where c.authors_count = 3;