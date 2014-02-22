alter table bpa add column nro_visita integer not null default 0;
create unique index  bpa_ruat_id_nro_visita on bpa(ruat_id,nro_visita);