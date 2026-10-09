
/****** Object:  Table [dbo].[WEBENGINE_GIFT_CODE_LOGS]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[WEBENGINE_GIFT_CODE_LOGS](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[Codigo] [varchar](50) NOT NULL,
	[Usuario] [varchar](10) NOT NULL,
	[Personaje] [varchar](10) NOT NULL,
	[Fecha] [date] NOT NULL
) ON [PRIMARY]


SET ANSI_PADDING OFF

SET IDENTITY_INSERT [dbo].[WEBENGINE_GIFT_CODE_LOGS] ON 

INSERT [dbo].[WEBENGINE_GIFT_CODE_LOGS] ([id], [Codigo], [Usuario], [Personaje], [Fecha]) VALUES (1, N'DIK62LJNWZHSYR5', N'Config', N'Config', CAST(N'2025-06-24' AS Date))
INSERT [dbo].[WEBENGINE_GIFT_CODE_LOGS] ([id], [Codigo], [Usuario], [Personaje], [Fecha]) VALUES (1, N'DIK62LJNWZHSYR5', N'Config', N'Config', CAST(N'2025-06-24' AS Date))
SET IDENTITY_INSERT [dbo].[WEBENGINE_GIFT_CODE_LOGS] OFF


