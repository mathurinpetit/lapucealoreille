rm lapucealoreille.db

sqlite3 lapucealoreille.db "create table daemodels ( id TEXT, path TEXT);";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('logo','logo.dae');";
#sqlite3 lapucealoreille.db "insert into daemodels VALUES ('classic_puce','puce_classic_without_texture.dae');";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('classic_puce_light','puce_classic_without_texture_light.dae');";
#sqlite3 lapucealoreille.db "insert into daemodels VALUES ('attache_or','attache_or.dae');";
#sqlite3 lapucealoreille.db "insert into daemodels VALUES ('attache_argent','attache_argent.dae');";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('attache_or_light','attache_or_light.dae');";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('attache_argent_light','attache_argent_light.dae');";

sqlite3 lapucealoreille.db "create table models (id INTEGER, model_libelle TEXT, puce_model TEXT, attache_model TEXT, texture TEXT, type_model TEXT, caracteristiques TEXT, description TEXT, image_0 TEXT, image_1 TEXT, image_2 TEXT, image_3 TEXT, qte INTEGER, paypal TEXT, scale_x NUMBER, scale_y NUMBER);";
sqlite3 lapucealoreille.db "insert into models VALUES (1,
                            'Classic Or Rond',
                            'classic_puce_light',
                            'attache_or_light',
                            'texture_or_papillon_carre',
                            'classic_or_petit_rond',
                            'Modèle : Classic Or \Type de puce : Classics Or rond (petit) \Attaches : Dorées ',
                '            Cette paire est constituée d''une puce Classic Or rond dont le centre est de petite taille (à contrario de la paire Classic Or rond 2), elle porte une attache simple dorée et fait partie de la collection des Classics. Premier modèle vendu par La Puce à l''oreille. C''est une boucle d''oreille élégante et simple qui reste néamoins originale. Entièrement faite à la main avec des puces de récupération, elle présente une mise en bijoux écologique d''un produit courant. Première paire des créations La Puce à l''oreille, Elle est le Must have de la marque et aussi le modèle le plus vendu.',
                'classic_or_petit_rond_0.jpg',
                'classic_or_petit_rond_1.jpg',
                'classic_or_petit_rond_2.jpg',
                'classic_or_petit_rond_3.jpg',3, 'MUVPGHFZKUA8N', 1, 1);";