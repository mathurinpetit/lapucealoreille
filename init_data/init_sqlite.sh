rm lapucealoreille.db
sqlite3 lapucealoreille.db "create table models (id INTEGER PRIMARY KEY, puce_model TEXT, attache_model TEXT, texture TEXT, type_model TEXT, caracteristiques TEXT, description TEXT, image_0 TEXT, image_1 TEXT, image_2 TEXT, image_3 TEXT, qte INTEGER);";
sqlite3 lapucealoreille.db "insert into models VALUES (1,'classic_puce','attache_or','texture_or_petit_rond','classic_or_petit_rond', 'SUPER PAIRE','SUPER PAIRE crée en ... par et de',null,null,null,null ,3);";
