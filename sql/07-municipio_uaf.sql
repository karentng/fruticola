alter table municipio add column resolucion_uaf varchar(20);

create table municipio_uaf(
    id serial primary key not null,
    municipio_id integer not null references municipio(id),
    descripcion varchar(150) not null,
    valor double precision not null
);


update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76020';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76036';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76041';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76054';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76100';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76109';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76111';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76113';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76122';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76001';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76126';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76130';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76147';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76233';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76243';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76246';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76248';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76250';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76275';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76306';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76318';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76364';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76677';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76400';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76403';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76497';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76520';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76563';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76606';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76616';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76622';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76670';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76736';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76823';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76828';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76834';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76845';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76863';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76869';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76890';
update municipio set resolucion_uaf='Resol. 1132 de 2013' where codigo='76892';
update municipio set resolucion_uaf='Resol. 041 de 1996' where codigo='76895';



insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76020'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',8);           
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76036'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',11);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76041'),'Valor de la UAF ÁREA PLANA del Municipio',6);     
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76041'),'Valor de la UAF ÁREA LADERA Coordillera Occidental',6);
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76054'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',5);           
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76100'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',9);           
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76109'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',24);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76111'),'Valor de la UAF ÁREA PLANA del Municipio',6); 
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76111'),'Valor de la UAF ÁREA LADERA Coordillera Central (1.000 - 2.000 msnm)',13);    
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76113'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',8);           
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76122'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',13);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76001'),'Valor de la UAF ÁREA PLANA del Municipio',6);     
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76001'),'Valor de la UAF ÁREA LADERA Coordillera Occidental',11);
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76126'),'Valor de la UAF ÁREA LADERA Coordillera Occidental',11);
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76130'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',5);           
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76147'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',10);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76233'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',13);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76243'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',14);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76246'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',18);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76248'),'Valor de la UAF ÁREA PLANA del Municipio',6); 
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76248'),'Valor de la UAF ÁREA LADERA Coordillera Central (1.000 - 2.000 msnm)',13);    
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76250'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',10);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76275'),'Valor de la UAF ÁREA PLANA del Municipio',6); 
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76275'),'Valor de la UAF ÁREA LADERA Coordillera Central (1.000 - 2.000 msnm)',13);    
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76306'),'Valor de la UAF ÁREA PLANA del Municipio',6); 
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76306'),'Valor de la UAF ÁREA LADERA Coordillera Central (1.000 - 2.000 msnm)',13);    
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76318'),'Valor de la UAF ÁREA PLANA del Municipio',6); 
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76318'),'Valor de la UAF ÁREA LADERA Coordillera Central (1.000 - 2.000 msnm)',13);    
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76364'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',11);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76677'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',11);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76400'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',4);           
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76403'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',14);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76497'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',7);           
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76520'),'Valor de la UAF ÁREA PLANA del Municipio',6); 
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76520'),'Valor de la UAF ÁREA LADERA Coordillera Central (1.000 - 2.000 msnm)',13);    
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76563'),'Valor de la UAF ÁREA PLANA del Municipio',6); 
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76563'),'Valor de la UAF ÁREA LADERA Coordillera Central (1.000 - 2.000 msnm)',13);    
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76606'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',13);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76616'),'Valor de la UAF ÁREA PLANA del Municipio',6);
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76616'),'Valor de la UAF ÁREA LADERA Coordillera Occidental',11);
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76622'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',8);           
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76670'),'Valor de la UAF ÁREA PLANA del Municipio',6); 
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76670'),'Valor de la UAF ÁREA LADERA Coordillera Central (1.000 - 2.000 msnm)',13);    
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76736'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',11);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76823'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',12);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76828'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',10);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76834'),'Valor de la UAF ÁREA PLANA del Municipio',6);
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76834'),'Valor de la UAF ÁREA LADERA Coordillera Central (1.000 - 2.000 msnm)',13);    
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76845'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',7);           
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76863'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',15);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76869'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',8);           
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76890'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',12);          
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76892'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',8);           
insert into municipio_uaf(municipio_id,descripcion,valor) values((select id from municipio where codigo='76895'),'Valor de la UAF TODA EL ÁREA MUNICIPAL',5);           
