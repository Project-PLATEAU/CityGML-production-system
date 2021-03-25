<?php
  global $wpdb;

  $sql ="DROP FUNCTION IF EXISTS ksk_SUBSTRING_INDEX;";
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql =<<<EOL
CREATE FUNCTION ksk_SUBSTRING_INDEX(str TEXT ,delim TEXT ,count int,item2 int)
  RETURNS TEXT
  DETERMINISTIC READS SQL DATA
  BEGIN
    IF count>0 THEN RETURN SUBSTRING_INDEX(SUBSTRING_INDEX(str,delim,count+item2-1),delim,-item2);
    ELSE RETURN SUBSTRING_INDEX(SUBSTRING_INDEX(str,delim,count),delim,item2);
   END IF;
  END
EOL
;
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);
  
  
  $sql ="DROP FUNCTION IF EXISTS ksk_SUBSTR_COUNT;";
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql =<<<EOL
CREATE FUNCTION ksk_SUBSTR_COUNT(ｔ1 TEXT ,t2 TEXT)
  RETURNS INT
  DETERMINISTIC READS SQL DATA
  BEGIN
    RETURN (LENGTH(ｔ1) -LENGTH(REPLACE(ｔ1, t2, ''))) /LENGTH(t2);
  END
EOL
;
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql ="DROP FUNCTION IF EXISTS ksk_StartPoint;";
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql =<<<EOL
CREATE FUNCTION ksk_StartPoint(g GEOMETRY)
  RETURNS GEOMETRY
  DETERMINISTIC READS SQL DATA
  BEGIN
    RETURN ST_GeomFromText(CONCAT('POINT(',SUBSTRING_INDEX(CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(ST_AsText(g),')',1),'(',-1),','),',',1) ,')'),SRID(g));
  END;
EOL
;
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql ="DROP FUNCTION IF EXISTS ksk_GeometryType;";
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql =<<<EOL
CREATE FUNCTION ksk_GeometryType(g GEOMETRY)
  RETURNS VARCHAR(40)
  DETERMINISTIC READS SQL DATA
  BEGIN
    RETURN SUBSTRING_INDEX(ST_AsText(g),'(',1);
  END;
EOL
;
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql ="DROP FUNCTION IF EXISTS ksk_GeomFromMeshcode;";
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql =<<<EOL
CREATE FUNCTION ksk_GeomFromMeshcode(meshcode VARCHAR(20))
  RETURNS GEOMETRY
  DETERMINISTIC READS SQL DATA
  BEGIN
    DECLARE slen INT;
    DECLARE Lat1 DOUBLE;
    DECLARE Lon1 DOUBLE;
    DECLARE Lat2 DOUBLE;
    DECLARE Lon2 DOUBLE;
    SET slen = LENGTH(meshcode);

    if slen < 5 THEN
      ##1次
      SET Lat1 = substr(meshcode ,1 ,2) /1.5;
      SET Lon1 = substr(meshcode ,3 ,2) +100;
      SET Lat2 = Lat1 +1/1.5;
      SET Lon2 = Lon1 +1;
    ELSE
      if slen < 7 THEN
        ##2次
        SET Lat1 = substr(meshcode ,1 ,2) /1.5 +substr(meshcode ,5 ,1) /12;
        SET Lon1 = substr(meshcode ,3 ,2) +100 +substr(meshcode ,6 ,1) /8;
        SET Lat2 = Lat1 +1/12;
        SET Lon2 = Lon1 +1/8;
      ELSE
        if slen < 9 THEN
          ##3次
          SET Lat1 = substr(meshcode ,1 ,2) /1.5 +substr(meshcode ,5 ,1) /12 +substr(meshcode ,7 ,1) /120;
          SET Lon1 = substr(meshcode ,3 ,2) +100 +substr(meshcode ,6 ,1) /8  +substr(meshcode ,8 ,1) /80;
          SET Lat2 = Lat1 +1/120;
          SET Lon2 = Lon1 +1/80;
        ELSE
          if slen < 10 THEN
            ##4次
            SET Lat1 = substr(meshcode ,1 ,2) /1.5 +substr(meshcode ,5 ,1) /12 +substr(meshcode ,7 ,1) /120 +TRUNCATE(substr(meshcode ,9 ,1) /2.5 ,0) /240;
            SET Lon1 = substr(meshcode ,3 ,2) +100 +substr(meshcode ,6 ,1) /8  +substr(meshcode ,8 ,1) /80  +((substr(meshcode ,9 ,1) +1) %2) /160;
            SET Lat2 = Lat1 +1/240;
            SET Lon2 = Lon1 +1/160;
          ELSE
            ##5次
            SET Lat1 = substr(meshcode ,1 ,2) /1.5 +substr(meshcode ,5 ,1) /12 +substr(meshcode ,7 ,1) /120 +TRUNCATE(substr(meshcode ,9 ,1) /2.5 ,0) /240 +TRUNCATE(substr(meshcode ,10 ,1) /2.5 ,0) /480;
            SET Lon1 = substr(meshcode ,3 ,2) +100 +substr(meshcode ,6 ,1) /8  +substr(meshcode ,8 ,1) /80  +((substr(meshcode ,9 ,1) +1) %2) /160 +((substr(meshcode ,10 ,1) +1) %2) /320;
            SET Lat2 = Lat1 +1/480;
            SET Lon2 = Lon1 +1/320;
          END IF;
        END IF;
      END IF;
    END IF;

    RETURN ST_GeomFromText(concat('MULTIPOLYGON(((',Lon1,' ', Lat1,',', Lon1,' ',Lat2,',',Lon2,' ',Lat2,',',Lon2,' ',Lat1,',',Lon1,' ',Lat1,')))'), 4326);
  END;
EOL
;
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql ="DROP FUNCTION IF EXISTS ksk_IsLeftHand;";
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql =<<<EOL
CREATE FUNCTION ksk_IsLeftHand(g GEOMETRY)
  RETURNS BOOLEAN
  DETERMINISTIC READS SQL DATA
  BEGIN
    DECLARE g2 GEOMETRY;
    DECLARE N INT;
    DECLARE S DOUBLE;
    DECLARE p1 INT;

    SELECT ExteriorRing(GeometryN(g,1)) into g2;
    SET N = NumPoints(g2);
    SET S = 0;
    SET p1 = 0;

    label1: LOOP
      SET p1 = p1 + 1;
      SET S = S + ST_X(PointN(g2,p1))*ST_Y(PointN(g2,p1+1))-ST_X(PointN(g2,p1+1))*ST_Y(PointN(g2,p1));
      IF p1 < N-1 THEN ITERATE label1; END IF;
      LEAVE label1;
    END LOOP label1;

    IF S>0 THEN RETURN TRUE;
      ELSEIF S<0 THEN RETURN FALSE;
      ELSE RETURN NULL;
    END IF;
  END
EOL
;
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql ="DROP FUNCTION IF EXISTS ksk_MeshcodeByCentroid;";
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql =<<<EOL
CREATE FUNCTION ksk_MeshcodeByCentroid(g GEOMETRY ,i int)
  RETURNS VARCHAR(12)
  DETERMINISTIC READS SQL DATA
  BEGIN
    DECLARE m LONG;
    DECLARE m1 INTEGER;
    DECLARE m2 INTEGER;
    DECLARE x DOUBLE;
    DECLARE y DOUBLE;
    SET x = ST_X(ksk_StartPoint(g));
    SET y = ST_Y(ksk_StartPoint(g));
    
    SET m = TRUNCATE(y *1.5 ,0);
    SET y = y - m /1.5;
    SET m2 = TRUNCATE(x ,0) -100;
    SET x = x - m2 -100;
    SET m = m *100 + m2;
    if i > 1 THEN
      SET m1 = TRUNCATE(y *12 ,0);
      SET y = y - m1 /12;
      SET m2 = TRUNCATE(x *8 ,0);
      SET x = x - m2 /8;
      SET m = m *100 +m1 *10 +m2;
      if i > 2 THEN
        SET m1 = TRUNCATE(y *120 ,0);
        SET y = y - m1 /120;
        SET m2 = TRUNCATE(x *80 ,0);
        SET x = x - m2 /80;
        SET m = m *100 +m1 *10 +m2;
        if i > 3 THEN
          SET m1 = TRUNCATE(y *240 ,0);
          SET y = y - m1 /240;
          SET m2 = TRUNCATE(x *160 ,0);
          SET x = x - m2 /160;
          SET m = m *10 +m1 *2 +1 +m2;
          if i > 4 THEN
            SET m1 = TRUNCATE(y *480 ,0);
            SET m2 = TRUNCATE(x *320 ,0);
            SET m = m *10 +m1 *2 +1 +m2;
          END IF;
        END IF;
      END IF;
    END IF;
    RETURN CAST(m as CHAR);
  END;
EOL
;
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);
  
  
  $sql ="DROP FUNCTION IF EXISTS ksk_Reverce;";
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql =<<<EOL
CREATE FUNCTION ksk_Reverce(g GEOMETRY)
  RETURNS GEOMETRY
  DETERMINISTIC READS SQL DATA
  BEGIN
    DECLARE gt1 TEXT;
    DECLARE gt2 TEXT;
    DECLARE str1 TEXT;
    DECLARE str2 TEXT;
    DECLARE N1 INT;
    DECLARE N2 INT;
    DECLARE i2 INT;
    DECLARE ptN INT;

    SET gt1 = ST_AsText(g);
    SET gt2 = "";
    
    SET N1 = LOCATE('(',gt1);
    label1: LOOP
      SET N2 = LOCATE(')',gt1,N1);
      SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(gt1,')',1),'(',-1) INTO str1;
      SET gt2 = CONCAT(gt2 ,LEFT(gt1 ,N2-LENGTH(str1)-1));
      SET gt1 = RIGHT(gt1 ,LENGTH(gt1)-N2);
      
      SET i2 =1;
      SET ptN = ksk_substr_count(str1 ,',');
      SET str2 = SUBSTRING_INDEX(str1,',',-1);
      if ptN>0 THEN
        label2: LOOP
          SET i2 = i2 +1;
          SET str2 = CONCAT(str2 ,',' ,ksk_SUBSTRING_INDEX(str1,',',-i2,1));
          IF i2 <= ptN THEN ITERATE label2; END IF;
          LEAVE label2;
        END LOOP label2;
      END IF;
      
      SET gt2 = CONCAT(gt2 ,str2 ,')');
      SET N1 = LOCATE('(' ,gt1);
      IF N1 > 0 THEN ITERATE label1; END IF;
      LEAVE label1;
    END LOOP label1;
    SET gt2 = CONCAT(gt2 ,gt1);

    RETURN ST_GeomFromText(gt2,SRID(g));
  END
EOL
;
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);
  
  $sql ="DROP FUNCTION IF EXISTS ksk_XMax;";
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql =<<<EOL
CREATE FUNCTION ksk_XMax(g GEOMETRY)
  RETURNS DOUBLE
  DETERMINISTIC READS SQL DATA
  BEGIN
    DECLARE g2 GEOMETRY;
    DECLARE t_type VARCHAR(40);
    DECLARE x DOUBLE;

    SET g2 = ST_Envelope(g);
    SET t_type = ksk_GeometryType(g2);

    IF t_type like '%polygon%' THEN
      SET x = ST_X(PointN(ExteriorRing(g2),3));
    ELSE
      IF t_type like '%polygon%' THEN
        SET x = ST_X(PointN(g2,2));
      ELSE
        SET x = ST_X(g2);
      END IF;
    END IF;

    RETURN x;
  END;
EOL
;
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql ="DROP FUNCTION IF EXISTS ksk_XMin;";
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql =<<<EOL
CREATE FUNCTION ksk_XMin(g GEOMETRY)
  RETURNS DOUBLE
  DETERMINISTIC READS SQL DATA
  BEGIN
    DECLARE g2 GEOMETRY;
    DECLARE t_type VARCHAR(40);
    DECLARE x DOUBLE;

    SET g2 = ST_Envelope(g);
    SET t_type = ksk_GeometryType(g2);

    IF t_type like '%polygon%' THEN
      SET x = ST_X(PointN(ExteriorRing(g2),1));
    ELSE
      IF t_type like '%polygon%' THEN
        SET x = ST_X(PointN(g2,1));
      ELSE
        SET x = ST_X(g2);
      END IF;
    END IF;

    RETURN x;
  END;
EOL
;
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql ="DROP FUNCTION IF EXISTS ksk_YMax;";
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql =<<<EOL
CREATE FUNCTION ksk_YMax(g GEOMETRY)
  RETURNS DOUBLE
  DETERMINISTIC READS SQL DATA
  BEGIN
    DECLARE g2 GEOMETRY;
    DECLARE t_type VARCHAR(40);
    DECLARE x DOUBLE;

    SET g2 = ST_Envelope(g);
    SET t_type = ksk_GeometryType(g2);

    IF t_type like '%polygon%' THEN
      SET x = ST_Y(PointN(ExteriorRing(g2),3));
    ELSE
      IF t_type like '%polygon%' THEN
        SET x = ST_Y(PointN(g2,2));
      ELSE
        SET x = ST_Y(g2);
      END IF;
    END IF;

    RETURN x;
  END;
EOL
;
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql ="DROP FUNCTION IF EXISTS ksk_YMin;";
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);

  $sql =<<<EOL
CREATE FUNCTION ksk_YMin(g GEOMETRY)
  RETURNS DOUBLE
  DETERMINISTIC READS SQL DATA
  BEGIN
    DECLARE g2 GEOMETRY;
    DECLARE t_type VARCHAR(40);
    DECLARE x DOUBLE;

    SET g2 = ST_Envelope(g);
    SET t_type = ksk_GeometryType(g2);

    IF t_type like '%polygon%' THEN
      SET x = ST_Y(PointN(ExteriorRing(g2),1));
    ELSE
      IF t_type like '%polygon%' THEN
        SET x = ST_Y(PointN(g2,1));
      ELSE
        SET x = ST_Y(g2);
      END IF;
    END IF;

    RETURN x;
  END;
EOL
;
  ksk3d_log( "sql:" .$sql );
  $wpdb->query($sql);
