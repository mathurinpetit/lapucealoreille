rm lapucealoreille.db

sqlite3 lapucealoreille.db "create table daemodels ( id TEXT, path TEXT);";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('logo','logo.dae');";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('classic_puce','puce_classic_without_texture.dae');";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('attache_or','attache_or.dae');";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('attache_argent','attache_argent.dae');";

sqlite3 lapucealoreille.db "create table models (id INTEGER, puce_model TEXT, attache_model TEXT, texture TEXT, type_model TEXT, caracteristiques TEXT, description TEXT, image_0 TEXT, image_1 TEXT, image_2 TEXT, image_3 TEXT, qte INTEGER);";
sqlite3 lapucealoreille.db "insert into models VALUES (1,
                            'classic_puce',
                            'attache_or',
                            'texture_or_petit_rond',
                            'classic_or_petit_rond',
                            'Modèle : Classics Or \n Type de puce : Classics Or rond (S) \n Attache : Or ',
                'Cette paire est constituée dune puce Classics Or rond Cette paire est constituée dune puce Classics Or rond dont le centre est rond et porte une attache simple dorée. Elle fait partie de la collection des Classics, première collection vendue par La Puce à loreille. Cest une boucle doreille élégante et simple tout en restant originale. Entièrement fait main avec des puces de récupération, elle présente une mise en bijoux écologique dun produit courant. Première paire des créations La Puce à loreille, Elle est le Must have de la marque.',
                'classic_or_petit_rond_0.jpg',
                'classic_or_petit_rond_1.jpg',
                'classic_or_petit_rond_2.jpg',
                'classic_or_petit_rond_0.jpg',3);";
sqlite3 lapucealoreille.db "insert into models VALUES (2,'classic_puce','attache_argent','texture_argent','classic_argent', 'SUPER PAIRE','SUPER PAIRE crée en ... par et de',null,null,null,null ,3);";
sqlite3 lapucealoreille.db "insert into models VALUES (3,'classic_puce','attache_argent','texture_argent_carre','classic_argent_carre', 'SUPER PAIRE','SUPER PAIRE crée en ... par et de',null,null,null,null ,3);";
