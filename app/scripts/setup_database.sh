#!/bin/bash
psql -h localhost -p 5432 -U forge -d postgres -W -c 'CREATE DATABASE test_db_1'
/home/ubuntu/testing/clones/new-clone/php artisan migrate