
/****** Object:  Table [dbo].[WEBENGINE_GIFT_CODE_PACKS]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_GIFT_CODE_PACKS](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[PackID] [int] NOT NULL,
	[ClassID] [int] NOT NULL,
	[Name] [varchar](50) NOT NULL,
	[ExpireItem] [int] NOT NULL,
	[ExpireGremory] [int] NOT NULL,
	[TypeGremory] [int] NOT NULL,
	[NamePack] [varchar](100) NOT NULL,
	[ItemFinalIndex] [int] NOT NULL,
	[Level] [int] NOT NULL,
	[Durabilidad] [int] NOT NULL,
	[Skill] [int] NOT NULL,
	[Luck] [int] NOT NULL,
	[Life] [int] NOT NULL,
	[ExeOP] [int] NOT NULL,
	[SetOP] [int] NOT NULL,
	[HHOP] [int] NOT NULL,
	[Op380] [int] NOT NULL,
	[Socket1] [int] NOT NULL,
	[Socket2] [int] NOT NULL,
	[Socket3] [int] NOT NULL,
	[Socket4] [int] NOT NULL,
	[Socket5] [int] NOT NULL,
	[iIndex] [int] NOT NULL,
	[iGroup] [int] NOT NULL
) ON [PRIMARY]


SET ANSI_PADDING OFF


