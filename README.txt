Livres de Donjons et Dragons :
[objet] : livre
[inventaire] : collection
[galerie] : bibliotheque

[inventaire] : - description de type String avec contrainte notnull
[objet] : - titre de type String avec contrainte notnull
- description de type String avec contrainte notnull
- niveau de type int avec contrainte notnull

[inventaire] (1) — (0..n) [objet] : OneToMany (un [inventaire] contient 0 à n [objet])

[membre] (1) - (0..n) [inventaire] : OneToMany (un [membre] contient 0 à n [inventaire]) 
