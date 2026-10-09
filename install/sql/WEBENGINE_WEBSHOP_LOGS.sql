
/****** Object:  Table [dbo].[WEBENGINE_WEBSHOP_LOGS]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[WEBENGINE_WEBSHOP_LOGS](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[Grupo] [int] NOT NULL,
	[Usuario] [varchar](50) NOT NULL,
	[Personaje] [varchar](50) NOT NULL,
	[Item] [varchar](100) NOT NULL,
	[NivelItem] [int] NOT NULL,
	[Skill] [int] NOT NULL,
	[Luck] [int] NOT NULL,
	[OpLife] [int] NOT NULL,
	[OpExe] [varchar](50) NOT NULL,
	[Acc] [int] NOT NULL,
	[Harmony] [int] NOT NULL,
	[Op380] [int] NOT NULL,
	[Socket1] [int] NOT NULL,
	[Socket2] [int] NOT NULL,
	[Socket3] [int] NOT NULL,
	[Socket4] [int] NOT NULL,
	[Socket5] [int] NOT NULL,
	[PrecioTotal] [int] NOT NULL,
	[Moneda] [varchar](50) NOT NULL,
	[SaldoRestante] [int] NOT NULL,
	[Fecha] [date] NOT NULL
) ON [PRIMARY]


SET ANSI_PADDING OFF

