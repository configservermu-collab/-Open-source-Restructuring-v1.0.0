
/****** Object:  Table [dbo].[WEBENGINE_GIFT_CODE]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[WEBENGINE_GIFT_CODE](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[TypeCode] [int] NOT NULL,
	[Codigo] [varchar](50) NOT NULL,
	[UsedMax] [int] NOT NULL,
	[UsedActual] [int] NOT NULL,
	[pLevel] [int] NOT NULL,
	[Niveles] [int] NOT NULL,
	[Resets] [int] NOT NULL,
	[MResets] [int] NOT NULL,
	[VipType] [int] NOT NULL,
	[VipDays] [int] NOT NULL,
	[TypeCoin] [varchar](15) NOT NULL,
	[Coins] [int] NOT NULL,
	[Usuario] [varchar](10) NOT NULL,
	[Personaje] [varchar](10) NOT NULL,
	[DarItem] [int] NOT NULL,
	[TypeWare] [int] NOT NULL,
	[iName] [varchar](100) NOT NULL,
	[iGroup] [int] NOT NULL,
	[iIndex] [int] NOT NULL,
	[iLevel] [int] NOT NULL
) ON [PRIMARY]


SET ANSI_PADDING OFF

SET IDENTITY_INSERT [dbo].[WEBENGINE_GIFT_CODE] ON 

INSERT [dbo].[WEBENGINE_GIFT_CODE] ([id], [TypeCode], [Codigo], [UsedMax], [UsedActual], [pLevel], [Niveles], [Resets], [MResets], [VipType], [VipDays], [TypeCoin], [Coins], [Usuario], [Personaje], [DarItem], [TypeWare], [iName], [iGroup], [iIndex], [iLevel]) VALUES (1, 0, N'KMS8EBFLCD37FL0', 1, 0, 0, 0, 0, 0, 0, 0, N'WCoinC', 0, N'0', N'0', 0, 0, N'Nombre Item', 14, 55, 0)
INSERT [dbo].[WEBENGINE_GIFT_CODE] ([id], [TypeCode], [Codigo], [UsedMax], [UsedActual], [pLevel], [Niveles], [Resets], [MResets], [VipType], [VipDays], [TypeCoin], [Coins], [Usuario], [Personaje], [DarItem], [TypeWare], [iName], [iGroup], [iIndex], [iLevel]) VALUES (2, 0, N'DIK62LJNWZHSYR5', 2, 1, 100, 100, 100, 0, 3, 30, N'WCoinC', 300, N'0', N'0', 0, 0, N'Nombre Item', 14, 55, 0)
INSERT [dbo].[WEBENGINE_GIFT_CODE] ([id], [TypeCode], [Codigo], [UsedMax], [UsedActual], [pLevel], [Niveles], [Resets], [MResets], [VipType], [VipDays], [TypeCoin], [Coins], [Usuario], [Personaje], [DarItem], [TypeWare], [iName], [iGroup], [iIndex], [iLevel]) VALUES (3, 0, N'U0SYS7VACC11PZ9', 1, 0, 100, 100, 100, 0, 3, 30, N'WCoinC', 1000, N'0', N'0', 0, 0, N'Nombre Item', 14, 55, 0)
INSERT [dbo].[WEBENGINE_GIFT_CODE] ([id], [TypeCode], [Codigo], [UsedMax], [UsedActual], [pLevel], [Niveles], [Resets], [MResets], [VipType], [VipDays], [TypeCoin], [Coins], [Usuario], [Personaje], [DarItem], [TypeWare], [iName], [iGroup], [iIndex], [iLevel]) VALUES (1, 0, N'KMS8EBFLCD37FL0', 1, 0, 0, 0, 0, 0, 0, 0, N'WCoinC', 0, N'0', N'0', 0, 0, N'Nombre Item', 14, 55, 0)
INSERT [dbo].[WEBENGINE_GIFT_CODE] ([id], [TypeCode], [Codigo], [UsedMax], [UsedActual], [pLevel], [Niveles], [Resets], [MResets], [VipType], [VipDays], [TypeCoin], [Coins], [Usuario], [Personaje], [DarItem], [TypeWare], [iName], [iGroup], [iIndex], [iLevel]) VALUES (2, 0, N'DIK62LJNWZHSYR5', 2, 1, 100, 100, 100, 0, 3, 30, N'WCoinC', 300, N'0', N'0', 0, 0, N'Nombre Item', 14, 55, 0)
INSERT [dbo].[WEBENGINE_GIFT_CODE] ([id], [TypeCode], [Codigo], [UsedMax], [UsedActual], [pLevel], [Niveles], [Resets], [MResets], [VipType], [VipDays], [TypeCoin], [Coins], [Usuario], [Personaje], [DarItem], [TypeWare], [iName], [iGroup], [iIndex], [iLevel]) VALUES (3, 0, N'U0SYS7VACC11PZ9', 1, 0, 100, 100, 100, 0, 3, 30, N'WCoinC', 1000, N'0', N'0', 0, 0, N'Nombre Item', 14, 55, 0)
SET IDENTITY_INSERT [dbo].[WEBENGINE_GIFT_CODE] OFF


