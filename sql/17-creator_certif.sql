alter table certificacion_visita add column creador_id integer null;
alter table certificacion_visita add column creado timestamp default current_timestamp;
alter table certificacion_visita add foreign key(creador_id) references users(id);

update certificacion_visita CV set creador_id=RU.creador_id, creado=fecha
from ruat RU 
where CV.ruat_id = RU.id;

