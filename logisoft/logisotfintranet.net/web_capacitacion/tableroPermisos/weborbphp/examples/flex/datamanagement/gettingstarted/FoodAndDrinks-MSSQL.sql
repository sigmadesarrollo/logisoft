USE master
GO
if exists (select * from sysdatabases where name='FoodAndDrinks')
        drop database FoodAndDrinks

GO

DECLARE @device_directory NVARCHAR(520)
SELECT @device_directory = SUBSTRING(filename, 1, CHARINDEX(N'master.mdf', LOWER(filename)) - 1)
FROM master.dbo.sysaltfiles WHERE dbid = 1 AND fileid = 1

EXECUTE (N'CREATE DATABASE FoodAndDrinks
  ON PRIMARY (NAME = N''FoodAndDrinks'', FILENAME = N''' + @device_directory + N'FoodAndDrinks_Data.mdf'')
  LOG ON (NAME = N''FoodAndDrinks_log'',  FILENAME = N''' + @device_directory + N'FoodAndDrinks_log.ldf'')')
go

exec sp_dboption 'FoodAndDrinks','trunc. log on chkpt.','true'
exec sp_dboption 'FoodAndDrinks','select into/bulkcopy','true'
GO

set quoted_identifier on
GO

/* Set DATEFORMAT so that the date strings are interpreted correctly regardless of
   the default DATEFORMAT on the server.
*/
SET DATEFORMAT mdy
GO
use "FoodAndDrinks"


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_Order_Account]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[order] DROP CONSTRAINT FK_Order_Account
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_OrderLine_Order]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[orderline] DROP CONSTRAINT FK_OrderLine_Order
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_OrderLine_Product]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[orderline] DROP CONSTRAINT FK_OrderLine_Product
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[account]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[account]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[order]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[order]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[orderline]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[orderline]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[product]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[product]
GO

CREATE TABLE [dbo].[account] (
    [AccountId] [int] IDENTITY (1, 1) NOT NULL ,
    [Login] [nvarchar] (40) COLLATE Cyrillic_General_CI_AS NOT NULL ,
    [Password] [nvarchar] (32) COLLATE Cyrillic_General_CI_AS NOT NULL 
) ON [PRIMARY]
GO

CREATE TABLE [dbo].[order] (
    [OrderId] [int] IDENTITY (1, 1) NOT NULL ,
    [AccountId] [int] NOT NULL ,
    [OrderDate] [datetime] NULL ,
    [DeliveryDate] [datetime] NULL ,
    [DeliveryAddress] [nvarchar] (150) COLLATE Cyrillic_General_CI_AS NULL ,
    [Status] [nvarchar] (10) COLLATE Cyrillic_General_CI_AS NOT NULL 
) ON [PRIMARY]
GO

CREATE TABLE [dbo].[orderline] (
    [OrderId] [int] NOT NULL ,
    [ProductId] [int] NOT NULL ,
    [Quanity] [smallint] NOT NULL ,
    [Price] [real] NOT NULL 
) ON [PRIMARY]
GO

CREATE TABLE [dbo].[product] (
    [ProductId] [int] IDENTITY (1, 1) NOT NULL ,
    [Name] [nvarchar] (100) COLLATE Cyrillic_General_CI_AS NOT NULL ,
    [Price] [real] NOT NULL ,
    [PictureFileName] [nvarchar] (100) COLLATE Cyrillic_General_CI_AS NOT NULL 
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[account] WITH NOCHECK ADD 
    CONSTRAINT [PK_Account] PRIMARY KEY  CLUSTERED 
    (
        [AccountId]
    )  ON [PRIMARY] 
GO

ALTER TABLE [dbo].[order] WITH NOCHECK ADD 
    CONSTRAINT [PK_Order] PRIMARY KEY  CLUSTERED 
    (
        [OrderId]
    )  ON [PRIMARY] 
GO

ALTER TABLE [dbo].[orderline] WITH NOCHECK ADD 
    CONSTRAINT [PK_OrderLine] PRIMARY KEY  CLUSTERED 
    (
        [OrderId],
        [ProductId]
    )  ON [PRIMARY] 
GO

ALTER TABLE [dbo].[product] WITH NOCHECK ADD 
    CONSTRAINT [PK_Product] PRIMARY KEY  CLUSTERED 
    (
        [ProductId]
    )  ON [PRIMARY] 
GO

ALTER TABLE [dbo].[order] ADD 
    CONSTRAINT [FK_Order_Account] FOREIGN KEY 
    (
        [AccountId]
    ) REFERENCES [dbo].[account] (
        [AccountId]
    )
GO

ALTER TABLE [dbo].[orderline] ADD 
    CONSTRAINT [FK_OrderLine_Order] FOREIGN KEY 
    (
        [OrderId]
    ) REFERENCES [dbo].[order] (
        [OrderId]
    ) ON DELETE CASCADE ,
    CONSTRAINT [FK_OrderLine_Product] FOREIGN KEY 
    (
        [ProductId]
    ) REFERENCES [dbo].[product] (
        [ProductId]
    ) ON DELETE CASCADE 
    
insert into product (Name, Price, PictureFileName) Values( 'Bananas', 1, 'bananas.jpg' )    
insert into product (Name, Price, PictureFileName) Values( 'Grapes', 5, 'grapes.jpg' )
insert into product (Name, Price, PictureFileName) Values( 'Lettuce', 8, 'green_leaf_lettuce.jpg' )
insert into product (Name, Price, PictureFileName) Values( 'Oranges', 12, 'orange.jpg' )
insert into product (Name, Price, PictureFileName) Values( 'Strawberries', 20, 'strawberries.jpg' )
insert into product (Name, Price, PictureFileName) Values( 'Apples', 8, 'apples.jpg' )
insert into account( Login, Password) Values ('Joe Grocer', 'goshopping' )
GO

