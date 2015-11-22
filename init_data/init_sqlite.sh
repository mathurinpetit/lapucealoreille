rm lapucealoreille.db

sqlite3 lapucealoreille.db "create table daemodels ( id TEXT, path TEXT);";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('logo','logo.dae');";
#sqlite3 lapucealoreille.db "insert into daemodels VALUES ('classic_puce','puce_classic_without_texture.dae');";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('classic_puce_light','puce_classic_without_texture_light.dae');";
#sqlite3 lapucealoreille.db "insert into daemodels VALUES ('attache_or','attache_or.dae');";
#sqlite3 lapucealoreille.db "insert into daemodels VALUES ('attache_argent','attache_argent.dae');";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('attache_or_light','attache_or_light.dae');";
sqlite3 lapucealoreille.db "insert into daemodels VALUES ('attache_argent_light','attache_argent_light.dae');";

sqlite3 lapucealoreille.db "create table models (id INTEGER, model_libelle TEXT, puce_model TEXT, attache_model TEXT, texture TEXT, type_model TEXT, caracteristiques TEXT, description TEXT, short_description TEXT, qte INTEGER, paypal TEXT, prix NUMBER);";

sqlite3 lapucealoreille.db "insert into models VALUES (1,
                            'Classic Or Rond',
                            'classic_puce_light',
                            'attache_or_light',
                            'texture_or_petit_rond',
                            'classic_or_petit_rond',
                            'Modèle : Classic Or \Type de puce : Or rond (petit) \Attaches : Dorées ',
                '            Cette paire est constituée d''une puce ""Classic Or rond"" dont le centre est de petite taille (à contrario de la paire Classic Or Carré), elle porte une attache simple dorée et fait partie de la collection des Classics. Premier modèle vendu par La Pusse à l''Oreille. C''est une boucle d''oreille élégante et simple qui reste néamoins originale. Entièrement faite à la main avec des puces de récupération, elle présente une mise en bijoux écologique d''un produit courant. Première paire des créations La Pusse à l''Oreille, Elle est le Must have de la marque et aussi le modèle le plus vendu.',
       'La paire ""Classic Or rond"", le plus grand des ""Classic"" de lapussealoreille. ',3, 'MUVPGHFZKUA8N', 7.5);";

sqlite3 lapucealoreille.db "insert into models VALUES (2,
                             'Classic Argent',
                             'classic_puce_light',
                             'attache_argent_light',
                             'texture_argent',
                             'classic_argent',
                             'Modèle : Classic Argent \Type de puce : Argent \Attaches : Argentées ',
                             'Cette paire est constituée d''une puce ""Classic Argent"". Elle porte une attache simple argenté et fait partie de la collection des ""Classics"". Sobre et élégante, elle possède une puce de taille légerement plus petite ce qui en fait un bijou discret. Comme toute les autres paires de ""La Pusse à l''Oreille"" elle est entièrement faite à la main avec des puces de récupération. Sa discretion et sa couleur argenté en fait un bijou passe partout qui ne ressemble pas à une puce éléctronique au première abord. Une des plus jolie paire de la collection.',
               'Paire constituée d''une puce ""Classic Argent"", elle est sobre et élégante. On ne voit pas qu''il s''agit d''une puce éléctronique.', 3, 'ULR6EJ6DCTNCY', 7.5);";


sqlite3 lapucealoreille.db "insert into models VALUES (3,
                            'Classic Or Carré',
                            'classic_puce_light',
                            'attache_or_light',
                            'texture_or_carre',
                            'classic_or_carre',
                            'Modèle : Classic Or Carré\Type de puce : Classics Or carrée \Attaches : Dorées ',
                '            Cette paire est constituée d''une puce ""Classic Or carré"" dont le centre est de petite taille, elle porte une attache simple de couleur dorée et fait partie de la collection des Classics. C''est le second modèle le plus vendu par La Pusse à l''Oreille. C''est une boucle d''oreille élégante et simple qui reste néamoins originale. Entièrement faite à la main avec des puces de récupération, elle présente une mise en bijoux écologique d''un produit courant. C''est une bonne alternative à la puce Classic ronde. Ce modèle offre un rendu plus proche d''une puce électronique.',
              'Cette paire est constituée d''une puce ""Classic Or carré"" dont le centre est de petite taille et joliment carré.',  3, 'AKT6QTRXW8F2L', 7.5);";


sqlite3 lapucealoreille.db "insert into models VALUES (4,
                            'Classic Argent Papillon(I)',
                            'classic_puce_light',
                            'attache_argent_light',
                            'texture_argent_papillon_plein',
                            'classic_argent_papillon_plein',
                            'Modèle : Classic Argent Papillon \Type de puce : Argent Papillon (I) \Attaches : Argentées ',
                '            Cette paire est constituée d''une puce Argent Papillon (I) plus large et plus robuste dont le centre en silicum vert donne un aspect rerto des plus intéressant. Comme toute paire de la collection des Classics cette boucle d''oreille élégante est entièrement fabriquée à la main avec des puces de récupération. L''originalité de ce bijoux écologiquement geek vous fera briller en soirée. Ce modèle constistue un format original plus rectangulaire que les autres. Son détourage blanc renforce les constrastes du bijou.',
         'Cette paire est constituée d''une puce Argent Papillon (I) plus large et plus robuste  en silicum vert. Aspect très ""robot"".',  3, 'NUWFSSL459BLQ', 7.5);";

sqlite3 lapucealoreille.db "insert into models VALUES (5,
                            'Classic Or Papillon Carré',
                            'classic_puce_light',
                            'attache_or_light',
                            'texture_or_papillon_carre',
                            'classic_or_papillon_carre',
                            'Modèle : Classic Or Papillon Carré \Type de puce : Or Papillon Carré \Attaches : Dorées ',
                '            Cette paire est constituée d''une puce Classic Or Papillon Carré dont le format est quasiment carré. De petite taille (1.4 x 1.4 cm), elle porte une attache simple dorée et fait partie de la collection des Classics. C''est un modèle de puce plus rare représentant une sorte de papillon doré en son centre. Cette boucle d''oreille élégante et simple reste très originale, de plus, elle est entièrement faite à la main. Au vu de la pauvreté de ce type de puce en France, elle est une des créations les plus demandées sur La Pusse à l''Oreille.',
 'Les ""Classic Or Papillon Carré"" dont le format carré de (1.4 x 1.4 cm), une paire rare !!!', 3, '58PJ9MYEE9GM8', 7.5);";

sqlite3 lapucealoreille.db "insert into models VALUES (6,
                            'Classic Argent Papillon (II)',
                            'classic_puce_light',
                            'attache_argent_light',
                            'texture_argent_papillon_creu',
                            'classic_argent_papillon_creu',
                            'Modèle : Classic Argent Papillon (II) \Type de puce : Argent Papillon (II) \Attaches : Argentées ',
                '            Contrairement à la puce Classic Argent Papillon (I), ce modèle est à la fois plus petit, et plus sophistiqué. Constitué d''une puce Classics ""Argent Papillon (II)"" en plastique blanc, la paire paraît presque transparente à la lumière du jour. De base elle possède une attache simple argentée, mais il est possible de la commander avec une attache de couleur ou d''un autre type sur simple demande téléphonique. La petite carrure de ce modèle en fait un bijou raffiné et discret qui sait ce faire remarquer par son aspect réfléchissant dans la lumière.',
           'La ""Classic Argent Papillon (II)"",  modèle plus petit, plus sophistiqué. Cette paire quasiment transparente s''accorde très bien avec les attaches de couleurs.',   3, 'TWTV4JDHM6X2L', 7.5);";

sqlite3 lapucealoreille.db "insert into models VALUES (1,
                            'Fais le toi-même',
                            null,
                             null,
                             null,
                            'diy',
                            'Modèle : A faire soi-même \Type de puce : Au choix dans la collection de lapussealoreille \Attaches : Au choix aussi ',
                '            Le Kit ""Fais-le toi-même"" comprend deux cartes téléphoniques, deux attaches de boucle d''oreille et un morceau de lime à ongle. Il vous propose de faire vous même votre propre modèle de la pusse à l''oreille en personalisant votre paire. Un tutoriel est disponible ci-dessous afin de créer une paire aussi jolie que celle proposées dans la boutique.<br/><br/> <iframe width=""560"" height=""315"" src=""https://www.youtube.com/embed/_NvqhNxjjIc"" frameborder=""0"" allowfullscreen></iframe>',
       'Le Kit ""Fais-le toi-même"" comprend deux cartes téléphoniques, deux attaches de boucle d''oreille et un morceau de lime à ongle. Tutoriel disponible ici : <br/> <iframe width=""260"" height=""115"" src=""https://www.youtube.com/embed/_NvqhNxjjIc"" frameborder=""0"" allowfullscreen></iframe> ',3, 'U5V6QWBT42W4S', 3.5);";

