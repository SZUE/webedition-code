CREATE TABLE tblWebAdmin (
  Name varchar(255) NOT NULL default '',
  Value text NOT NULL,
  PRIMARY KEY (Name)
) 
/* query separator */
INSERT INTO tblWebAdmin VALUES ('FieldAdds','a:13:{s:8:"Username";a:1:{s:4:"type";s:5:"input";}s:8:"Password";a:1:{s:4:"type";s:5:"input";}s:8:"Forename";a:1:{s:4:"type";s:5:"input";}s:7:"Surname";a:1:{s:4:"type";s:5:"input";}s:11:"LoginDenied";a:1:{s:4:"type";s:5:"input";}s:11:"MemberSince";a:1:{s:4:"type";s:5:"input";}s:9:"LastLogin";a:1:{s:4:"type";s:5:"input";}s:10:"LastAccess";a:1:{s:4:"type";s:5:"input";}s:15:"AutoLoginDenied";a:1:{s:4:"type";s:5:"input";}s:9:"AutoLogin";a:1:{s:4:"type";s:5:"input";}s:13:"Anrede_Anrede";a:2:{s:7:"default";s:10:",Herr,Frau";s:4:"type";s:6:"select";}s:13:"Newsletter_Ok";a:2:{s:7:"default";s:3:",ja";s:4:"type";s:6:"select";}s:25:"Newsletter_HTMLNewsletter";a:2:{s:7:"default";s:3:",ja";s:4:"type";s:6:"select";}}');
/* query separator */
INSERT INTO tblWebAdmin VALUES ('Prefs','a:4:{s:10:"start_year";s:4:"1900";s:17:"default_sort_view";s:20:"--Keine Sortierung--";s:15:"treetext_format";s:30:"#Username (#Forename #Surname)";s:13:"default_order";s:0:"";}');
/* query separator */
INSERT  INTO tblWebAdmin VALUES ('SortView','');
/* query separator */
INSERT  INTO tblWebAdmin VALUES ('default_saveRegisteredUser_register','false');
