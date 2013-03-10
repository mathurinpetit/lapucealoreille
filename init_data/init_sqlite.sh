rm lapucealoreille.db

sqlite3 lapucealoreille.db "create table daemodels ( id TEXT, path TEXT);";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('logo','logo.dae');";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('classic_puce','puce_classic_without_texture.dae');";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('attache_or','attache_or.dae');";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('attache_argent','attache_argent.dae');";

sqlite3 lapucealoreille.db "create table models (id INTEGER, model_libelle TEXT, puce_model TEXT, attache_model TEXT, texture TEXT, type_model TEXT, caracteristiques TEXT, description TEXT, image_0 TEXT, image_1 TEXT, image_2 TEXT, image_3 TEXT, qte INTEGER);";
sqlite3 lapucealoreille.db "insert into models VALUES (1,
                            'Classic Or Rond'
                            'classic_puce',
                            'attache_or',
                            'texture_or_petit_rond',
                            'classic_or_petit_rond',
                            'Modèle : Classic Or \n Type de puce : Classics Or rond (petit) \n Attache : Or ',
                'Cette paire est constituée dune puce Classic Or rond dont le centre est de petite taille (à contrario de la paire Classic Or rond 2), elle porte une attache simple dorée et fait partie de la collection des Classics. Premier modèle vendu par La Puce à loreille. Cest une boucle doreille élégante et simple qui reste néamoins originale. Entièrement faite à la main avec des puces de récupération, elle présente une mise en bijoux écologique dun produit courant. Première paire des créations La Puce à loreille, Elle est le Must have de la marque et aussi le modèle le plus vendu.',
                'classic_or_petit_rond_0.jpg',
                'classic_or_petit_rond_1.jpg',
                'classic_or_petit_rond_2.jpg',
                'classic_or_petit_rond_3.jpg',3);";
sqlite3 lapucealoreille.db "insert into models VALUES (2,
                             'Classic Argent',
                             'classic_puce',
                             'attache_argent',
                             'texture_argent',
                             'classic_argent',
                             'Modèle : Classic Argent \n Type de puce : Classics Argent \n Attache : Argent ',
                             'Cette paire est constituée dune puce Classic Argent. Elle porte une attache simple argenté et fait partie de la collection des Classics. Sobre et élégante, elle possède une puce de taille légerement plus petite ce qui en fait un bijou discret. Cette paire, entièrement fait main avec des puces de récupération, elle présente une mise en bijoux écologique dun produit courant, remplie doriginalité. Cest une des paire les plus apprécié de la boutique La Puce à loreille',
                'classic_argent_0.jpg',
                'classic_argent_1.jpg',
                'classic_argent_2.jpg',
                'classic_argent_3.jpg',3);";
sqlite3 lapucealoreille.db "insert into models VALUES (3,'classic_puce','attache_argent','texture_argent_carre','classic_argent_carre', 'SUPER PAIRE','SUPER PAIRE crée en ... par et de',null,null,null,null ,3);";
