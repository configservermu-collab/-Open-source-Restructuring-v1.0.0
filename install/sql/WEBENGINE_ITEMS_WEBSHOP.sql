
/****** Object:  Table [dbo].[WEBENGINE_ITEMS_WEBSHOP]    Script Date: 06/12/2024 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[WEBENGINE_ITEMS_WEBSHOP](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[iGrupo] [int] NOT NULL,
	[iIndex] [int] NOT NULL,
	[Name] [varchar](100) NOT NULL,
	[Descripcion] [varchar](500) NULL,
	[Precio] [int] NOT NULL,
	[Activo] [int] NOT NULL DEFAULT ((1)),
	[Socket] [int] NOT NULL DEFAULT ((1)),
	[Harmony] [int] NOT NULL DEFAULT ((1)),
	[Ancient] [int] NOT NULL DEFAULT ((1)),
	[Op380] [int] NOT NULL DEFAULT ((1)),
	[MinLevel] [int] NOT NULL DEFAULT ((0)),
	[MaxLevel] [int] NOT NULL DEFAULT ((15)),
	[MinLevelLife] [int] NOT NULL DEFAULT ((0)),
	[MaxLevelLife] [int] NOT NULL DEFAULT ((7)),
	[MinOpExe] [int] NOT NULL DEFAULT ((0)),
	[MaxOpExe] [int] NOT NULL DEFAULT ((6)),
	[MaxSockets] [int] NOT NULL DEFAULT ((5)),
	[X] [int] NOT NULL DEFAULT ((1)),
	[Y] [int] NOT NULL DEFAULT ((1)),
 CONSTRAINT [PK_WEBENGINE_ITEMS_WEBSHOP] PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

