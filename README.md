### 用户表
- set user:userid:1:username Rainie
- set user:userid:1:password 111
- set user:username:Rainie:userid 1

- incr global:userid

### 微博表
- set post:postid:1:content 'This are some contents'
- set post:postid:1:time timestamp
- set post:postid:1:userid 1

- incr global:postid